<?php

namespace App\Controller\Api\Front;

use App\Entity\Customer;
use App\Form\RegisterCustomerApiType;
use App\Repository\AboutUsRepository;
use App\Repository\CountriesRepository;
use App\Repository\CoverImageRepository;
use App\Repository\CustomersTypesRolesRepository;
use App\Repository\FaqsRepository;
use App\Repository\TopicsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helpers\EnqueueEmail;
use App\Constants\Constants;
use App\Entity\Product;
use App\Form\ContactType;
use App\Form\ListPriceType;
use App\Helpers\SendCustomerToCrm;
use App\Repository\AdvertisementsRepository;
use App\Repository\BrandRepository;
use App\Repository\BrandsSectionsRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitiesRepository;
use App\Repository\CommunicationStatesBetweenPlatformsRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomerStatusTypeRepository;
use App\Repository\FavoriteProductRepository;
use App\Repository\ProductRepository;
use App\Repository\RegistrationTypeRepository;
use App\Repository\SectionsHomeRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatesRepository;
use App\Repository\TagRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Uid\Uuid;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Service\AwsSnsClient;
use DateTime;
use ReCaptcha\ReCaptcha;

#[Route("/api/front")]
class FrontApiController extends AbstractController
{


    #[Route("/register", name: "api_register_customer", methods: ["POST"])]
    public function register(

        EntityManagerInterface $em,
        Request $request,
        CustomerStatusTypeRepository $customerStatusTypeRepository,
        RegistrationTypeRepository $registrationTypeRepository,
        // EnqueueEmail $queue,
        AwsSnsClient $awsSnsClient
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        //find relational objects
        // $country = $countriesRepository->find($data['country_id']);
        $status_customer = $customerStatusTypeRepository->find(Constants::CUSTOMER_STATUS_PENDING);
        $registration_type = $registrationTypeRepository->find(Constants::REGISTRATION_TYPE_WEB);


        //set Customer data
        $customer = new Customer();
        // AGREGO NULO A LOS VALORES QUE NO VINIERON EN EL JSON
        @$data['name'] ?? $data['name'] = null;
        @$data['email'] ?? $data['email'] = null;
        @$data['password'] ?? $data['password'] = null;
        @$data['code_area'] ?? $data['code_area'] = null;
        @$data['cel_phone'] ?? $data['cel_phone'] = null;
        @$data['state'] ?? $data['state'] = null;
        @$data['city'] ?? $data['city'] = null;
        @$data['street_address'] ?? $data['street_address'] = null;
        @$data['number_address'] ?? $data['number_address'] = null;
        @$data['floor_apartment'] ?? $data['floor_apartment'] = null;
        @$data['identity_number'] ?? $data['identity_number'] = null;
        @$data['policies_agree'] ?? $data['policies_agree'] = null;
        @$data['recaptcha_token'] ?? $data['recaptcha_token'] = null;


        $verification_code = mt_rand(100000, 999999);
        $customer
            ->setVerificationCode($verification_code)
            ->setDatetimeVerificationCode(new \DateTime)
            ->setStatus($status_customer)
            ->setRegistrationType($registration_type)
            ->setRegistrationDate(new \DateTime);

        $form = $this->createForm(RegisterCustomerApiType::class, $customer);
        $form->submit($data, false);

        if (!$form->isValid()) {
            $error_forms = $this->getErrorsFromForm($form);
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Error de validación',
                    'validation' => $error_forms
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
        try {
            $em->persist($customer);
            $em->flush();

            //envio SMS
            $awsSnsClient->sendSMS($_ENV['SMS_REGISTER_MSG'] . $verification_code, '+549' . $customer->getCodeArea() . $customer->getCelPhone());

            return $this->json(
                [
                    'status' => true,
                    'message' => 'Usuario registrado, esperando validación',
                    'url_validacion' => $_ENV['FRONT_URL'] . $_ENV['FRONT_VALIDATION'] . '?email=' . $customer->getEmail()
                ],
                Response::HTTP_CREATED,
                ['Content-Type' => 'application/json']
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Error al intentar registrar usuario: ' . $e->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route("/validate", name: "api_validate_customer", methods: ["POST"])]
    public function validate(

        EntityManagerInterface $em,
        Request $request,
        CustomerRepository $customerRepository,
        CustomerStatusTypeRepository $customerStatusTypeRepository,
        EnqueueEmail $queue,
    ): Response {
        $body = $request->getContent();
        $data = json_decode($body, true);

        //get Customer data
        if (!@$data['email'] || !@$data['validation_code'] || !@$data['recaptcha_token']) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Los campos email, validation_code, y recaptcha_token son obligatorios'
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
        $recaptcha = new ReCaptcha($_ENV['GOOGLE_RECAPTCHA_SECRET']);
        $recaptchaResponse = $recaptcha->verify($data['recaptcha_token']);

        if (!$recaptchaResponse->isSuccess()) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Token reCAPTCHA no válido.'
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
        $customer = $customerRepository->findOneBy(['email' => @$data['email']]);
        if (!$customer || !($customer->getStatus()->getId() === Constants::CUSTOMER_STATUS_PENDING)) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'No fue posible encontrar el usuario.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        if (!password_verify($data['validation_code'], $customer->getVerificationCode())) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Código incorrecto.'
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
        
        try {

            //find relational objects
            $status_customer = $customerStatusTypeRepository->find(Constants::CUSTOMER_STATUS_VALIDATED);

            $customer->setStatus($status_customer)
                ->setVerificationCode(null)
                ->setDatetimeVerificationCode(null);
            $em->persist($customer);
            $em->flush();

            //queue the email
            $id_email = $queue->enqueue(
                Constants::EMAIL_TYPE_WELCOME, //tipo de email
                $customer->getEmail(), //email destinatario
                [ //parametros
                    'name' => $customer->getName(),
                    'url_front_login' => $_ENV['FRONT_URL'] . $_ENV['FRONT_LOGIN'],
                ]
            );

            //Intento enviar el correo encolado
            $queue->sendEnqueue($id_email);

            return $this->json(
                [
                    'status' => true,
                    'message' => 'Su cuenta fue verificada con éxito.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Ocurrió un error: ' . $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route("/new_validation_code", name: "api_new_validation_code_customer", methods: ["POST"])]
    public function newValidationCode(
        EntityManagerInterface $em,
        Request $request,
        CustomerRepository $customerRepository,
        AwsSnsClient $awsSnsClient
    ): Response {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $errors = [];
        //get Customer data
        if (!@$data['email']) {
            $errors[] = 'El campo email es obligatorio';
        }
        if ((@$data['code_area'] && !@$data['cel_phone']) || (@$data['cel_phone'] && !@$data['code_area'])) {
            $errors[] = 'Si desea modificar el numero destinatario del sms son requeridos los campos code_area y cel_phone';
        }

        if (!@$data['recaptcha_token']) {
            $errors[] = 'Token reCAPTCHA es obligatorio.';
        }
        
        $recaptcha = new ReCaptcha($_ENV['GOOGLE_RECAPTCHA_SECRET']);
        $recaptchaResponse = $recaptcha->verify($data['recaptcha_token']);

        if (!$recaptchaResponse->isSuccess()) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Token reCAPTCHA no válido.'
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }
        if ($errors) {
            return $this->json(
                [
                    'status' => false,
                    'message' => $errors
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        $customer = $customerRepository->findOneBy(['email' => @$data['email']]);

        if (!$customer || !($customer->getStatus()->getId() === Constants::CUSTOMER_STATUS_PENDING)) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'No fue posible encontrar el usuario.'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        $dateVerificationCode =  $customer->getDatetimeVerificationCode();
        $dateVerificationCode->modify('+' . $_ENV['SMS_TIME_LIMIT'] . ' minutes');
        $currentDateTime = new DateTime();

        if ($currentDateTime < $dateVerificationCode) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Aun no es posible enviar un nuevo código de verificación, aguarde unos instantes.'
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        try {
            $verification_code = mt_rand(100000, 999999);


            if (@$data['code_area'] && @$data['cel_phone']) {
                $customer->setCodeArea($data['code_area'])
                    ->setCelPhone($data['cel_phone']);
            }

            $customer->setVerificationCode($verification_code)
                ->setDatetimeVerificationCode($currentDateTime);
            $em->persist($customer);
            $em->flush();

            //envio SMS
            $awsSnsClient->sendSMS($_ENV['SMS_REGISTER_MSG'] . $verification_code, '+549' . $customer->getCodeArea() . $customer->getCelPhone());

            return $this->json(
                [
                    'status' => true,
                    'message' => 'Se envió un nuevo código por sms al celular ' . $customer->getCodeArea() . $customer->getCelPhone() . '.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'Ocurrió un error: ' . $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route("/login", name: "api_login", methods: ["POST"])]
    public function login(Request $request, FavoriteProductRepository $favoriteProductRepository, ShoppingCartRepository $shoppingCartRepository, CustomerRepository $customerRepository, PasswordHasherFactoryInterface $passwordHasherFactoryInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (!(@$data['email'] && @$data['password'])) {
            $validation = [];
            if (!@$data['email']) {
                $validation["email"] =  "Debe ingresar una dirección de correo.";
            }
            if (!@$data['password']) {
                $validation["password"] =  "El campo password es obligatorio.";
            }
            if (!@$data['recaptcha_token']) {
                $validation["recaptcha_token"] = 'Token reCAPTCHA es obligatorio.';
            }
            
            return $this->json(
                [
                    "status" => false,
                    'message' => 'Error de validacion',
                    'validation' => $validation
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
            
            $recaptcha = new ReCaptcha($_ENV['GOOGLE_RECAPTCHA_SECRET']);
            $recaptchaResponse = $recaptcha->verify($data['recaptcha_token']);
    
            if (!$recaptchaResponse->isSuccess()) {
                return $this->json(
                    [
                        'status' => false,
                        'message' => 'Token reCAPTCHA no válido.'
                    ],
                    Response::HTTP_BAD_REQUEST,
                    ['Content-Type' => 'application/json']
                );
            }
        }

        try {
            $customer = $customerRepository->findOneBy(["email" => @$data['email']]);
            if (!$passwordHasherFactoryInterface->getPasswordHasher($customer)->verify($customer->getPassword(), $data['password'])) {
                throw new Exception();
            }
            if ($customer->getStatus()->getId() == Constants::CUSTOMER_STATUS_PENDING) {
                return $this->json(
                    [
                        "status" => false,
                        'message' => 'Su cuenta aún no fue validada.',
                        'url_validacion' => $_ENV['FRONT_URL'] . $_ENV['FRONT_VALIDATION'] . '?email=' . $customer->getEmail()
                    ],
                    Response::HTTP_UNAUTHORIZED,
                    ['Content-Type' => 'application/json']
                );
            }
            if ($customer->getStatus()->getId() == Constants::CUSTOMER_STATUS_DISABLED) {
                return $this->json(
                    [
                        "status" => false,
                        'message' => 'Su cuenta se encuentra deshabilitada, por favor contacte a atención al cliente.',
                    ],
                    Response::HTTP_UNAUTHORIZED,
                    ['Content-Type' => 'application/json']
                );
            }
        } catch (\Exception $e) {
            return $this->json(
                [
                    "status" => false,
                    'message' => 'Usuario y/o password incorrectos.',
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        $jwt = $jwtManager->create($customer);

        $favorite_products = $favoriteProductRepository->findAllFavoriteProductsByStatus($customer->getId(), 1);
        $favorite_products_list = [];
        if ($favorite_products) {
            foreach ($favorite_products as $favorite_product) {
                $favorite_products_list[] = (int)$favorite_product->getProduct()->getId();
            }
        }

        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($customer->getId(), 1);
        $shopping_cart_products_list = [];
        if ($shopping_cart_products) {
            foreach ($shopping_cart_products as $favorite_product) {
                $shopping_cart_products_list[] = (int)$favorite_product->getProduct()->getId();
            }
        }

        return new JsonResponse([
            "status" => true,
            "token" => $jwt,
            "token_type" => "Bearer",
            "expires_in" => (int)$_ENV['JWT_TOKEN_TTL'],
            "user_data" => [
                "id" => (int)$customer->getId(),
                "name" => $customer->getName(),
                "image" => $customer->getImage(),
                "email" => $customer->getEmail(),
                "wish_list" => $favorite_products_list,
                "shop_cart" => $shopping_cart_products_list,
                "code_area" => $customer->getCodeArea(),
                "state_id" => $customer->getState()->getId(),
                "state_name" => $customer->getState()->getName(),
                "city_id" => $customer->getCity()->getId(),
                "city_name" => $customer->getCity()->getName(),
                "cel_phone" => $customer->getCelPhone(),
                "cel_phone_complete" => '+549' . $customer->getCodeArea() . $customer->getCelPhone(),
            ]
        ]);
    }


    #[Route("/states", name: "api_get_states", methods: ["GET"])]
    public function getStates(CountriesRepository $countriesRepository, StatesRepository $statesRepository): Response
    {
        $states = $statesRepository->findVisibleStatesByCountryId(['country_id' => 11]); //11 = argentina
        return $this->json(
            [
                'status' => true,
                'states' => $states
            ],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/cities/{state_id?}", name: "api_get_cities_by_state_id", methods: ["GET"])]
    public function getCities($state_id, CitiesRepository $citiesRepository): Response
    {
        $cities = $citiesRepository->findVisibleCitiesByStateId(['state' => (int)$state_id]);
        if ($cities) {
            return $this->json(
                [
                    'status' => true,
                    'cities' => $cities
                ],
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        }
        return $this->json(
            [
                'status' => false,
                'message' => 'No se encotraron localidades/ciudades con el ID indicado.'
            ],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }


    //DE ACA PARA ABAJO SIN REVISAR

    #[Route("/searchTypeList", name: "api_search_type_list", methods: ["GET"])]
    public function searchTypeList(CategoryRepository $categoryRepository, TagRepository $tagRepository, BrandRepository $brandRepository): Response
    {

        $principalCategories = $categoryRepository->getPrincipalCategories();
        $principalBrands = $brandRepository->getPrincipalBrands();
        $principalTags = $tagRepository->getPrincipalTags();

        $searchList = [
            [
                "title" => 'Todos',
                "slug" => '',
                "items" => [],
            ],
            [
                "title" => 'Categorías',
                "slug" => "c=",
                "items" => $principalCategories
            ],
            [
                "title" => 'Marcas',
                "slug" => "b=",
                "items" => $principalBrands
            ],
            [
                "title" => 'Otros',
                "slug" => "t=",
                "items" => $principalTags
            ],
        ];

        return $this->json(
            $searchList,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/contact", name: "api_contanct", methods: ["POST"])]
    public function contact(EnqueueEmail $queue, Request $request, CountriesRepository $countriesRepository): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);


        try {
            $data['country'] = $countriesRepository->findOneBy(["id" => @$data['country_id']]);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error de validación',
                    'validation' => ['country_id' => 'No fue posible encontrar un pais con el codigo indicado.']
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }


        $form = $this->createForm(ContactType::class);
        $form->submit($data, false);

        if (!$form->isValid()) {
            $error_forms = $this->getErrorsFromForm($form);
            return $this->json(
                [
                    'message' => 'Error de validación',
                    'validation' => $error_forms
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        //queue the email
        $id_email = $queue->enqueue(
            Constants::EMAIL_TYPE_CONTACT, //tipo de email
            $_ENV['EMAIL_FROM'],
            [ //parametros
                "name" => $data['name'],
                "phone" => $data['country']->getPhonecode() . $data['phone'],
                'email' => $data['email'],
                "message" => $data['message'],
            ]
        );

        //Intento enviar el correo encolado
        $queue->sendEnqueue($id_email);


        return $this->json(
            ['message' => 'Formulario de contacto enviado correctamente.'],
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/listPrice", name: "api_list_price", methods: ["POST"])]
    public function listPrice(EnqueueEmail $queue, Request $request): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $form = $this->createForm(ListPriceType::class);
        $form->submit($data, false);

        if (!$form->isValid()) {
            $error_forms = $this->getErrorsFromForm($form);
            return $this->json(
                [
                    'message' => 'Error de validación',
                    'validation' => $error_forms
                ],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        //queue the email
        $id_email = $queue->enqueue(
            Constants::EMAIL_TYPE_PRICE_LIST, //tipo de email
            $_ENV['EMAIL_FROM'],
            [ //parametros
                'email' => $data['email'],
            ]
        );

        //Intento enviar el correo encolado
        $queue->sendEnqueue($id_email);


        return $this->json(
            ['message' => 'Solicitud enviada con exito.'],
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/products/searchaaaa", name: "api_products_searchaaaa", methods: ["GET"])]
    public function search(Request $request, CategoryRepository $categoryRepository, TagRepository $tagRepository, ProductRepository $productRepository): Response
    {

        $tag = $tagRepository->findTagVisibleBySlug('destacados');
        if ($tag) {

            $categoryLaptops = $categoryRepository->findOneBySlug('laptops');
            $categoryCelulares = $categoryRepository->findOneBySlug('celulares');
            $categoryAudio = $categoryRepository->findOneBySlug('audio');

            $limit = $request->query->getInt('l', 4);
            $index = $request->query->getInt('i', 0) * $limit;

            $productsLaptops = $productRepository->findProductsVisibleByTag($tag, $categoryLaptops, $limit, $index);
            $productsCelulares = $productRepository->findProductsVisibleByTag($tag, $categoryCelulares, $limit, $index);
            $productsAudio = $productRepository->findProductsVisibleByTag($tag, $categoryAudio, $limit, $index);


            //productos por placas de video
            $productsByCategory = [];

            foreach ($productsAudio as $productAudio) {
                $productsByCategory[] = $productAudio->getBasicDataProduct();
            }

            $products[] = [
                "category" => "Audio",
                "products" => $productsByCategory
            ];

            // productos por categoria laptops
            $productsByCategory = [];

            foreach ($productsLaptops as $productLaptop) {
                $productsByCategory[] = $productLaptop->getBasicDataProduct();
            }

            $products[] = [
                "category" => 'Laptops',
                "products" => $productsByCategory
            ];

            // productos por categoria celulares

            $productsByCategory = [];

            foreach ($productsCelulares as $productCelular) {
                $productsByCategory[] = $productCelular->getBasicDataProduct();
            }

            $products[] = [
                "category" => "Celulares",
                "products" => $productsByCategory
            ];



            return $this->json(
                $products,
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } else {
            return $this->json(
                ['message' => 'Not found'],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route("/products/search", name: "api_products_search", methods: ["GET"])]
    public function productsSearch(
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        BrandRepository $brandRepository,
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $keywords = $request->query->get('k', null);

        $array_categories = json_decode($request->query->get('c', null), true);
        if ($array_categories) {
            $categories = $categoryRepository->findCategoriesBySlug($array_categories);
        }

        $array_brands = json_decode($request->query->get('b', null), true);
        if ($array_brands) {
            $brands = $brandRepository->findBrandsBySlug($array_brands);
        }

        $array_tags = json_decode($request->query->get('t', null), true);
        if ($array_tags) {
            $tags = $tagRepository->findTagsBySlug($array_tags);
        }

        // Definicion de filtros
        // k=keyword
        // c=categorias,
        // b=marcas,
        // t=etiquetas,
        // i=indice,
        // l=limit,
        // [
        //     id,
        //     name,
        //     brand,
        //     price,
        //     old_price,
        //     reviews,
        //     rating,
        //     image
        // ]

        $limit = $request->query->getInt('l', 4);
        $index = $request->query->getInt('i', 0) * $limit;

        if ($keywords) {
            $array_keywords_minus = explode(' ', strtolower($keywords));
            array_push($array_keywords_minus, strtolower($keywords));

            $array_keywords_mayus = explode(' ', strtoupper($keywords));
            array_push($array_keywords_mayus, strtoupper($keywords));

            $array_keywords = array_merge($array_keywords_minus, $array_keywords_mayus);
        }


        $filters = [];

        if (isset($categories)) {
            $filters[] = [
                "column" => 'category',
                "method" => 'IN',
                "parameters" => $categories,
            ];
        }
        if (isset($brands)) {
            $filters[] = [
                "column" => 'brand',
                "method" => 'IN',
                "parameters" => $brands,
            ];
        }
        if (isset($tags)) {
            $filters[] = [
                "column" => 'tag',
                "method" => 'IN',
                "parameters" => $tags,
            ];
        }


        $products = $productRepository->findProductByFilters($filters, $limit, $index, isset($array_keywords) ? $array_keywords : null);


        if ($products) {
            $products_founded = [];
            foreach ($products as $product) {
                $products_founded[] = $product->getBasicDataProduct();
            }

            return $this->json(
                $products_founded,
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        }

        return $this->json(
            [],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/products/tag/{slug_tag}", name: "api_products_tag", methods: ["GET"])]
    public function productsTag($slug_tag, Request $request, CategoryRepository $categoryRepository, TagRepository $tagRepository, ProductRepository $productRepository): Response
    {

        $tag = $tagRepository->findTagVisibleBySlug($slug_tag);
        if ($tag) {

            $limit = $request->query->getInt('l', 4);
            $index = $request->query->getInt('i', 0) * $limit;

            $code = $request->query->getInt('code', 0);
            $order = $request->query->getInt('order', 0);

            if ($code === 0) {
                $code = mt_rand(1, 15);
                $order = ['asc', 'desc'][mt_rand(0, 1)];
            }

            $products = $productRepository->findProductRandom($code, $order, $tag);
            dd($products);

            // REPLICAR LA SIGUIENTE CONSULTA EN EL PRODUCTrEPOSITORY
            // SELECCIONA EL NRO RAMDON SI ES QUE NO TIENE CODE, SI YA LA TIENE 
            // SELECT substring(name,7,1) as letra FROM public.mia_product
            // ORDER BY letra ASC 

            return $this->json(
                $products,
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } else {
            return $this->json(
                ['message' => 'Not found'],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }
    }


    #[Route("/products/sections", name: "api_products_sections", methods: ["GET"])]
    public function sections(Request $request, ProductRepository $productRepository, SectionsHomeRepository $sectionsHomeRepository): Response
    {
        //traigo la linea 1 de secciones.
        $sections = $sectionsHomeRepository->findAll()[0];

        if ($sections) {

            $products_by_sections = [];

            $limit = $request->query->getInt('l', 4);
            $index = $request->query->getInt('i', 0) * $limit;

            for ($i = 1; $i <= 4; $i++) {
                //creo variables para luego utilizarlas como funciones para poder traerme los datos de cada seccion
                $getTitleSectionN = "getTitleSection" . $i;
                $getTagSectionN = "getTagSection" . $i;

                //genero un array con cada seccion con las categorias de cada seccion
                $products_by_sections[] =
                    [
                        "title" => $sections->$getTitleSectionN(),
                        "categories" => []
                    ];

                for ($j = 1; $j <= 3; $j++) {
                    //genero variable para utilizarla luego como funcion
                    $getCategoryNSectionN = "getCategory" . $j . "Section" . $i;

                    $products = $productRepository->findProductsVisibleByTag($sections->$getTagSectionN(), $sections->$getCategoryNSectionN(), $limit, $index);

                    $productsByCategory = [];

                    foreach ($products as $product) {
                        $productsByCategory[] = $product->getBasicDataProduct();
                    }
                    if ($sections->$getCategoryNSectionN()) {
                        $products_by_sections[$i - 1]['categories'][] =
                            [
                                "category" => $sections->$getCategoryNSectionN()->getName(),
                                "products" => $productsByCategory
                            ];
                    }
                }
            }

            return $this->json(
                $products_by_sections,
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        } else {
            return $this->json(
                ['message' => 'Not found'],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route("/product/{product_id}", name: "api_product_detail", methods: ["GET"])]
    public function productDetail(Request $request, ProductRepository $productRepository, $product_id): Response
    {
        //traigo el producto 1
        $product = $productRepository->findActiveProductById($product_id);

        if ($product) {

            //busco productos similares
            $other_colors = $productRepository->findColorsAvailable($product->getSku(), $product_id);

            $others_models = $product->getStorage() ? $productRepository->findSimilarProductBySku($product->getSku(), $product_id) : false;


            $images = [];
            foreach ($product->getImage() as $product_image) {
                $images[] = [
                    'image' => $product_image->getImage(),
                    'thumbnail' => $product_image->getImgThumbnail(),
                    'principal' => $product_image->getPrincipal() ? true : false,
                ];
            }
            $breadcrumbs = [];
            $breadcrumbs[] = $product->getCategory()->getName();
            if ($product->getSubcategory()) {
                $breadcrumbs[] = $product->getSubcategory()->getName();
            }
            $breadcrumbs[] = $product->getBrand()->getName();

            $products_by_models = [];
            if ($others_models) {
                foreach ($others_models as $other_model) {
                    $products_by_models[] = [
                        "product_id" => $other_model->getId(),
                        "storage" => $other_model->getStorage() ? $other_model->getStorage()->getName() : null,
                        "active" =>  $other_model->getId() == $product_id ? true : false,
                    ];
                }
            }

            $products_by_color = [];
            if ($other_colors) {
                foreach ($other_colors as $other_color) {
                    $products_by_color[] = [
                        "product_id" => $other_color['color'] == ($product->getColor() ? $product->getColor()->getName() : null) ? (int) $product_id : $other_color['product_id'],
                        "color" => $other_color['color'],
                        "colorHexadecimal" => $other_color['colorHexadecimal'],
                        "active" => $other_color['color'] == ($product->getColor() ? $product->getColor()->getName() : null) ? true : false,
                    ];
                }
            }

            $productJson = [
                "id" => $product->getId(),
                "name" => $product->getName(),
                "slug" => $product->getSlug(),
                "breadcrumbs" => $breadcrumbs,
                "category" => $product->getCategory() ? $product->getCategory()->getName() : null,
                "subcategory" => $product->getSubcategory() ? $product->getSubcategory()->getName() : null,
                "brand" => $product->getBrand() ? $product->getBrand()->getName() : null,
                "model" => $product->getModel() ? $product->getModel()->getName() : null,
                "sku" => $product->getSku(),
                "price" => $product->getDiscountActive() ?  ($product->getPrice() - (($product->getPrice() / 100) * $product->getDiscountActive())) : $product->getPrice(),
                "old_price" => $product->getDiscountActive() ? $product->getPrice() : null,
                "available" => $product->getAvailable(),
                "short_description_es" => $product->getDescriptionEs(),
                "long_description_es" => $product->getLongDescriptionEs(),
                "short_description_en" => $product->getDescriptionEn(),
                "long_description_en" => $product->getLongDescriptionEn(),
                "images" => $images,
                "tag" => $product->getTag() ? $product->getTag()->getName() : null,
                "rating" => (int)$product->getRating(),
                "reviews" => (int)$product->getReviews(),
                "conditium" => $product->getConditium() ? $product->getConditium()->getName() : null,
                // "storage" => $product->getStorage() ? $product->getStorage()->getName() : null,
                // "color" => $product->getColor() ? $product->getColor()->getName() : null,
                // "colorHex" => $product->getColor() ? $product->getColor()->getColorHexadecimal() : null,
                "products_by_color" => $products_by_color ?: null,
                "products_models" => $products_by_models ?: null,
                "product_detail" => [
                    "weight" => $product->getWeight() ? $product->getWeight() : null,
                    "screen_resolution" => $product->getScreenResolution() ? $product->getScreenResolution()->getName() : null,
                    "screen_size" => $product->getScreenSize() ? $product->getScreenSize()->getName() : null,
                    "cpu" => $product->getCpu() ? $product->getCpu()->getName() : null,
                    "gpu" => $product->getGpu() ? $product->getGpu()->getName() : null,
                    "memory" => $product->getMemory() ? $product->getMemory()->getName() : null,
                    "os" => $product->getOpSys() ? $product->getOpSys()->getName() : null,
                    "cod" => $product->getCod(),
                    "part_number" => $product->getPartNumber(),
                ],
            ];

            return $this->json(
                $productJson,
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        }

        return $this->json(
            ['message' => 'Not found'],
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/sliders", name: "api_sliders", methods: ["GET"])]
    public function sliders(CoverImageRepository $coverImageRepository): Response
    {

        $sliders = $coverImageRepository->findCoverImage();

        return $this->json(
            $sliders,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/banners", name: "api_banners", methods: ["GET"])]
    public function banners(AdvertisementsRepository $advertisementsRepository): Response
    {

        $banners = $advertisementsRepository->find(1);

        return $this->json(
            $banners->getBanners(),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/brands", name: "api_brands", methods: ["GET"])]
    public function brands(BrandsSectionsRepository $brandsSectionsRepository): Response
    {

        $brands = $brandsSectionsRepository->find(1);

        return $this->json(
            $brands->getBrands(),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/about-us", name: "api_about_us", methods: ["GET"])]
    public function aboutUs(AboutUsRepository $aboutUsRepository): Response
    {

        $aboutUs = $aboutUsRepository->findAboutUsDescription();
        return $this->json(
            $aboutUs,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/faqs", name: "api_faqs", methods: ["GET"])]
    public function faqs(FaqsRepository $faqsRepository, TopicsRepository $topicsRepository): Response
    {
        $topics = $topicsRepository->getTopics();
        for ($i = 0; $i < count($topics); $i++) {
            $topics[$i]['faqs'] = $faqsRepository->getFaqsByTopic($topics[$i]['topic_id']);
        }
        return $this->json(
            $topics,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/customer-type", name: "api_customer_type", methods: ["GET"])]
    public function customerType(CustomersTypesRolesRepository $customersTypesRolesRepository): Response
    {

        $customersTypeRoles = $customersTypesRolesRepository->findCustomerTypesRole();
        return $this->json(
            $customersTypeRoles,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/country-code", name: "api_country_code", methods: ["GET"])]
    public function countryCode(CountriesRepository $countriesRepository): Response
    {

        $countries = $countriesRepository->getCountries();
        return $this->json(
            $countries,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/categoriesList", name: "api_categories_list", methods: ["GET"])]
    public function categoriesList(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->getVisibleCategories();

        $categoryObject = [];

        foreach ($categories as $category) {
            array_push(
                $categoryObject,
                [
                    "id" => $category->getId(),
                    "name" => $category->getName(),
                    "description_es" => $category->getDescriptionEs(),
                    "description_en" => $category->getDescriptionEn(),
                    "principal" => $category->getPrincipal(),
                    "image" => $category->getImage(),
                    "slug" => 'c=' . $category->getId(),
                ]
            );
        }
        return $this->json(
            $categoryObject,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }


    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                    if (isset($errors['email'][0]) && count($errors['email']) > 1) {
                        unset($errors['email'][0]);
                        // Reindexar el array si es necesario
                        $errors['email'] = array_values($errors['email']);
                    }
                }
            }
        }
        return $errors;
    }
}
