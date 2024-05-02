<?php

namespace App\Controller\Api\Front;

use App\Entity\Customer;
use App\Form\RegisterCustomerApiType;
use App\Repository\CountriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helpers\EnqueueEmail;
use App\Constants\Constants;
use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\CitiesRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomerStatusTypeRepository;
use App\Repository\FavoriteProductRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductsSalesPointsRepository;
use App\Repository\RegistrationTypeRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatesRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
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

        if (!(@$data['email'] && @$data['password'] && @$data['recaptcha_token'])) {
            $validation = [];
            if (!@$data['email']) {
                $validation["email"] =  "Debe ingresar una dirección de correo.";
            }
            if (!@$data['password']) {
                $validation["password"] =  "El campo password es obligatorio.";
            }
            if (!@$data['recaptcha_token']) {
                var_dump('aca');
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


    #[Route("/menu_categories", name: "api_menu_categories", methods: ["GET"])]
    public function menuCategories(CategoryRepository $categoryRepository, SubcategoryRepository $subcategoryRepository): Response
    {

        $categories = $categoryRepository->findBy(['visible' => true], ['name' => 'ASC']);
        $categoriesArray = [];
        foreach ($categories as $category) {
            $categoriesArray[] = [
                'id' => $category->getId(),
                'search_parameter' => 'c=',
                'slug' => $category->getSlug(),
                'name' => $category->getName(),
                'subcategories' => $subcategoryRepository->getSubcategoriesVisiblesByCategory($category),
            ];
        }

        return $this->json(
            [
                'status' => true,
                'categories' => $categoriesArray
            ],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/sales_points", name: "api_sales_points", methods: ["GET"])]
    public function salesPoints(UserRepository $userRepository): Response
    {

        $salesPoints = $userRepository->findStatesAndCities();

        $states = [];

        foreach ($salesPoints as $salePoint) {
            $stateId = $salePoint['state_id'];
            $stateName = $salePoint['state_name'];
            $cityId = $salePoint['city_id'];
            $cityName = $salePoint['city_name'];

            $stateFound = false;
            foreach ($states as &$state) {
                if ($state['id'] == $stateId) {
                    $stateFound = true;
                    $city = ['id' => $cityId, 'name' => $cityName];
                    $state['cities'][] = $city;
                    break;
                }
            }

            if (!$stateFound) {
                $newState = [
                    'name' => $stateName,
                    'id' => $stateId,
                    'cities' => [
                        ['id' => $cityId, 'name' => $cityName]
                    ]
                ];
                $states[] = $newState;
            }
        }

        return $this->json(
            [
                'status' => true,
                'sales_points' => $states
            ],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/products", name: "api_products", methods: ["GET"])]
    public function products(
        ProductsSalesPointsRepository $productsSalesPointsRepository,
    ): Response {
        $productosDestacados = $productsSalesPointsRepository->findProductsByTagName('DESTACADOS', 8);
        $productosOfertas = $productsSalesPointsRepository->findProductsByTagName('OFERTAS', 8);
        $productosAccesorios = $productsSalesPointsRepository->findProductsByCategoryName('ACCESORIOS', 8);
        $productosRepuestos = $productsSalesPointsRepository->findProductsByCategoryName('REPUESTOS', 8);

        $importantProducts = [];
        foreach ($productosDestacados as $product) {
            $importantProducts[] = $product->getDataBasicProductFront();
        }
        $offerProducts = [];
        foreach ($productosOfertas as $product) {
            $offerProducts[] = $product->getDataBasicProductFront();
        }
        $accesoriesProducts = [];
        foreach ($productosAccesorios as $product) {
            $accesoriesProducts[] = $product->getDataBasicProductFront();
        }
        $repacementPartsProducts = [];
        foreach ($productosRepuestos as $product) {
            $repacementPartsProducts[] = $product->getDataBasicProductFront();
        }
        $productsFront = [
            [
                'title' => 'Podria interesarte',
                'products' => $importantProducts
            ],
            [
                'title' => 'Ofertas',
                'products' => $offerProducts
            ],
            [
                'title' => 'Accesorios',
                'products' => $accesoriesProducts
            ],
            [
                'title' => 'Repuestos',
                'products' => $repacementPartsProducts
            ]
        ];

        return $this->json(
            $productsFront,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route("/products/search", name: "api_products_search", methods: ["GET"])]
    public function productsSearch(
        // CategoryRepository $categoryRepository,
        // TagRepository $tagRepository,
        // BrandRepository $brandRepository,
        ProductsSalesPointsRepository $productsSaleProductsSalesPointsRepository,
        Request $request,
    ): Response {
        // Definicion de filtros
        // k=keyword
        // sc=subcategorias,
        // s=estados/provincias (id)
        // ci=ciudades/localidades (id)
        // i=indice,
        // l=limit,
        $keywords = $request->query->get('k', null);
        $category = $request->query->get('c', null);
        $subcategory = $request->query->get('sc', null);
        $state = $request->query->get('s', null);
        $city = $request->query->get('ci', null);



        //NO DEFINIDAS AUN
        // c=categorias,
        // b=marcas,
        // t=etiquetas,

        // $array_categories = json_decode($request->query->get('c', null), true);
        // if ($array_categories) {
        //     $categories = $categoryRepository->findCategoriesBySlug($array_categories);
        // }
        // $array_brands = json_decode($request->query->get('b', null), true);
        // if ($array_brands) {
        //     $brands = $brandRepository->findBrandsBySlug($array_brands);
        // }
        // $array_tags = json_decode($request->query->get('t', null), true);
        // if ($array_tags) {
        //     $tags = $tagRepository->findTagsBySlug($array_tags);
        // }


        //DEFINIR LIMITE INICIAL.
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

        if (isset($category)) {
            $filters[] = [
                "table" => 'p',
                "name" => 'category',
                "value" => (int)$category,
            ];
        }

        if (isset($subcategory)) {
            $filters[] = [
                "table" => 'p',
                "name" => 'subcategory',
                "value" => (int)$subcategory,
            ];
        }

        if (isset($state)) {
            $filters[] = [
                "table" => 'sp',
                "name" => 'state',
                "value" => (int)$state,
            ];
        }

        if (isset($city)) {
            $filters[] = [
                "table" => 'sp',
                "name" => 'city',
                "value" => (int)$city,
            ];
        }

        $productsSalesPoints = $productsSaleProductsSalesPointsRepository->findProductByFilters(isset($array_keywords) ? $array_keywords : null, $filters, $limit, $index);

        if ($productsSalesPoints) {
            $products_founded = [];
            foreach ($productsSalesPoints as $productSalePoint) {
                $products_founded[] = $productSalePoint->getDataBasicProductFront();
            }

            return $this->json(
                [
                    'status' => true,
                    'products' => $products_founded
                ],
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

    // #[Route("/products/tag/{slug_tag}", name: "api_products_tag", methods: ["GET"])]
    // public function productsTag($slug_tag, Request $request, CategoryRepository $categoryRepository, TagRepository $tagRepository, ProductRepository $productRepository): Response
    // {

    //     $tag = $tagRepository->findTagVisibleBySlug($slug_tag);
    //     if ($tag) {

    //         $limit = $request->query->getInt('l', 4);
    //         $index = $request->query->getInt('i', 0) * $limit;

    //         $code = $request->query->getInt('code', 0);
    //         $order = $request->query->getInt('order', 0);

    //         if ($code === 0) {
    //             $code = mt_rand(1, 15);
    //             $order = ['asc', 'desc'][mt_rand(0, 1)];
    //         }

    //         $products = $productRepository->findProductRandom($code, $order, $tag);
    //         dd($products);

    //         // REPLICAR LA SIGUIENTE CONSULTA EN EL PRODUCTrEPOSITORY
    //         // SELECCIONA EL NRO RAMDON SI ES QUE NO TIENE CODE, SI YA LA TIENE 
    //         // SELECT substring(name,7,1) as letra FROM public.mia_product
    //         // ORDER BY letra ASC 

    //         return $this->json(
    //             $products,
    //             Response::HTTP_OK,
    //             ['Content-Type' => 'application/json']
    //         );
    //     } else {
    //         return $this->json(
    //             ['message' => 'Not found'],
    //             Response::HTTP_NOT_FOUND,
    //             ['Content-Type' => 'application/json']
    //         );
    //     }
    // }


    #[Route("/product/{product_id}", name: "api_product_detail", methods: ["GET"])]
    public function productDetail(ProductsSalesPointsRepository $productsSalesPointsRepository, $product_id): Response
    {
        $productSalePoint = $productsSalesPointsRepository->findActiveProductById($product_id);

        if ($productSalePoint) {
            $images = [];
            foreach ($productSalePoint->getProduct()->getImage() as $product_image) {
                $images[] = [
                    'image' => $product_image->getImage(),
                    'thumbnail' => $product_image->getImgThumbnail(),
                    'principal' => $product_image->getPrincipal() ? true : false,
                ];
            }

            $tags = [];
            foreach ($productSalePoint->getProductSalePointTags() as $product_tag) {
                $tags[] = [
                    'name' => $product_tag->getTag()->getName(),
                    'id' => $product_tag->getTag()->getId(),
                ];
            }

            $breadcrumbs = [];
            $breadcrumbs[] = $productSalePoint->getProduct()->getCategory()->getName();
            if ($productSalePoint->getProduct()->getSubcategory()) {
                $breadcrumbs[] = $productSalePoint->getProduct()->getSubcategory()->getName();
            }
            $breadcrumbs[] = $productSalePoint->getProduct()->getBrand()->getName();

            $productJson = [
                "id" => $productSalePoint->getId(),
                "name" => $productSalePoint->getProduct()->getName(),
                "slug" => $productSalePoint->getProduct()->getSlug(),
                "breadcrumbs" => $breadcrumbs,
                "category" => $productSalePoint->getProduct()->getCategory() ? $productSalePoint->getProduct()->getCategory()->getName() : null,
                "subcategory" => $productSalePoint->getProduct()->getSubcategory() ? $productSalePoint->getProduct()->getSubcategory()->getName() : null,
                "brand" => $productSalePoint->getProduct()->getBrand() ? $productSalePoint->getProduct()->getBrand()->getName() : null,
                "model" => $productSalePoint->getProduct()->getModel() ? $productSalePoint->getProduct()->getModel()->getName() : null,
                "price" => $productSalePoint->getLastPrice() ? $productSalePoint->getLastPrice()->getPrice() : null,
                // "available" => $productSalePoint->getProduct()->getAvailable(),
                "short_description" => $productSalePoint->getProduct()->getDescription(),
                "long_description" => $productSalePoint->getProduct()->getLongDescription(),
                "images" => $images,
                "tags" => $tags,
                "product_detail" => [
                    "screen_resolution" => $productSalePoint->getProduct()->getScreenResolution() == '' ? $productSalePoint->getProduct()->getScreenResolution()->getName() : null,
                    "screen_size" => $productSalePoint->getProduct()->getScreenSize() == '' ? $productSalePoint->getProduct()->getScreenSize()->getName() : null,
                    "cpu" => $productSalePoint->getProduct()->getCpu() == '' ? $productSalePoint->getProduct()->getCpu()->getName() : null,
                    "gpu" => $productSalePoint->getProduct()->getGpu() == '' ? $productSalePoint->getProduct()->getGpu()->getName() : null,
                    "memory" => $productSalePoint->getProduct()->getMemory() == '' ? $productSalePoint->getProduct()->getMemory()->getName() : null,
                    "os" => $productSalePoint->getProduct()->getOS() == '' ? $productSalePoint->getProduct()->getOS()->getName() : null,
                    "cod" => $productSalePoint->getProduct()->getCod(),
                ],
                "state" => $productSalePoint->getSalePoint()->getState()->getName(),
                "city" => $productSalePoint->getSalePoint()->getCity()->getName(),

            ];

            return $this->json(
                [
                    "status" => true,
                    "product" => $productJson
                ],
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        }

        return $this->json(
            [
                "status" => false,
                'message' => 'Producto no encontrado.',
            ],
            Response::HTTP_NOT_FOUND,
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
