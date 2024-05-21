<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Entity\PaymentsFiles;
use App\Entity\ProductSalePointInventory;
use App\Entity\ShoppingCart;
use App\Repository\CitiesRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrdersRepository;
use App\Repository\PaymentTypeRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductsSalesPointsRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\StatesRepository;
use App\Repository\StatusOrderTypeRepository;
use App\Repository\StatusTypeShoppingCartRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Snappy\Pdf;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/api/customer")]
class CustomerApiController extends AbstractController
{

    private $customer;

    public function __construct(JWTEncoderInterface $jwtEncoder, CustomerRepository $customerRepository, RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $token = explode(' ', $request->headers->get('Authorization'))[1];

        $username = @$jwtEncoder->decode($token)['username'] ?: '';

        $this->customer = $customerRepository->findOneBy(['email' => $username]);
    }

    #[Route("/data", name: "api_customer_data", methods: ["GET", "PATCH"])]
    public function data(
        Request $request,
        StatesRepository $statesRepository,
        CitiesRepository $citiesRepository,
        EntityManagerInterface $em,
        CustomerRepository $customerRepository,
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        switch ($request->getMethod()) {
            case 'GET':
                return $this->json(
                    [
                        'status' => true,
                        "user_data" => [
                            "id" => (int)$this->customer->getId(),
                            "name" => $this->customer->getName(),
                            "identity_number" => $this->customer->getIdentityNumber(),
                            "email" => $this->customer->getEmail(),
                            "state_id" => $this->customer->getState()->getId(),
                            "state_name" => $this->customer->getState()->getName(),
                            "city_id" => $this->customer->getCity()->getId(),
                            "city_name" => $this->customer->getCity()->getName(),
                            "code_area" => $this->customer->getCodeArea(),
                            "cel_phone" => $this->customer->getCelPhone(),
                            "street_address" => $this->customer->getStreetAddress(),
                            "number_address" => $this->customer->getNumberAddress(),
                            "floor_apartment" => $this->customer->getFloorApartment()
                        ]
                    ],
                    Response::HTTP_ACCEPTED,
                    ['Content-Type' => 'application/json']
                );
                break;
            case 'PATCH':
                if (!@$data['name']) {
                    $errors['name'] = 'El campo name es requerido';
                }
                if (!@$data['identity_number']) {
                    $errors['identity_number'] = 'El campo identity_number es requerido';
                }
                if (!@$data['email']) {
                    $errors['email'][] = 'El campo email es requerido';
                }
                if (!@$data['state_id']) {
                    $errors['state_id'][] = 'El campo state_id es requerido';
                }
                if (!@$data['city_id']) {
                    $errors['city_id'][] = 'El campo city_id es requerido';
                }
                if (!@$data['street_address']) {
                    $errors['street_address'] = 'El campo street_address es requerido';
                }
                if (!@$data['number_address']) {
                    $errors['number_address'] = 'El campo number_address es requerido';
                }
                if (!@$data['postal_code']) {
                    $errors['postal_code'] = 'El campo postal_code es requerido';
                }
                if (!@$data['code_area']) {
                    $errors['code_area'] = 'El campo code_area es requerido';
                }
                if (!@$data['cel_phone']) {
                    $errors['cel_phone'] = 'El campo cel_phone es requerido';
                }

                if (@$data['email']) {
                    if ($this->customer->getEmail() != @$data['email']) {
                        $customer = $customerRepository->findOneBy(['email' => @$data['email']]);
                        if ($customer) {
                            $errors['email'][] = 'Esta cuenta ya se encuentra registrada';
                        }
                    }
                }

                if (@$data['state_id']) {
                    $state = $statesRepository->find(@$data['state_id']);
                    if (!$state) {
                        $errors['state_id'][] = 'La provincia indicada no existe.';
                    }
                }
                if (@$data['city_id']) {
                    $city = $citiesRepository->find(@$data['city_id']);
                    if (!$city) {
                        $errors['city_id'][] = 'La ciudad indicada no existe.';
                    }
                }

                if (!empty($errors)) {
                    $response = [
                        "status" => false,
                        'message' => 'Error al intentar modificar sus datos de perfil.',
                        "errors" => $errors
                    ];
                    return $this->json($response, Response::HTTP_CONFLICT, ['Content-Type' => 'application/json']);
                }

                $this->customer
                    ->setName(@$data['name'])
                    ->setIdentityNumber(@$data['identity_number'])
                    ->setEmail(@$data['email'])
                    ->setState($state)
                    ->setCity($city)
                    ->setCodeArea(@$data['code_area'])
                    ->setCelPhone(@$data['cel_phone'])
                    ->setStreetAddress(@$data['street_address'])
                    ->setNumberAddress(@$data['number_address'])
                    ->setFloorApartment(@$data['floor_apartment']);

                $em->persist($this->customer);
                $em->flush();
                $response = [
                    "status" => true,
                    "message" => "Usuario modificado correctamente"
                ];
                return $this->json($response, Response::HTTP_ACCEPTED, ['Content-Type' => 'application/json']);
                break;
        }
    }

    #[Route("/changePassword", name: "api_customer_change_password", methods: ["POST"])]
    public function changePassword(
        Request $request,
        StatesRepository $statesRepository,
        CitiesRepository $citiesRepository,
        EntityManagerInterface $em,
        CustomerRepository $customerRepository,
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        if (!@$data['password']) {
            $errors['password'] = 'El campo passwprd es requerido';
        }
        if (!@$data['repassword']) {
            $errors['repassword'] = 'El campo repassword es requerido';
        }

        if (@$data['password'] !== @$data['repassword']) {
            $errors['repassword'] = 'El campo password y repassword deben coincidir';
        }

        if (!empty($errors)) {
            $response = [
                "status" => false,
                'message' => 'Error al intentar modificar sus datos de perfil.',
                "errors" => $errors
            ];
            return $this->json($response, Response::HTTP_CONFLICT, ['Content-Type' => 'application/json']);
        }

        $this->customer->setPassword(@$data['password']);
        $em->persist($this->customer);
        $em->flush();
        return $this->json(
            [
                'status' => true,
                'message' => 'Password modificada correctamente'
            ],
            Response::HTTP_CONFLICT,
            ['Content-Type' => 'application/json']
        );
    }
}
