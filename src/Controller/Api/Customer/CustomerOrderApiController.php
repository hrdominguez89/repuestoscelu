<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
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
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/api/customer")]
class CustomerOrderApiController extends AbstractController
{

    private $customer;

    public function __construct(JWTEncoderInterface $jwtEncoder, CustomerRepository $customerRepository, RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $token = explode(' ', $request->headers->get('Authorization'))[1];

        $username = @$jwtEncoder->decode($token)['username'] ?: '';

        $this->customer = $customerRepository->findOneBy(['email' => $username]);
    }

    #[Route("/order", name: "api_customer_order", methods: ["POST"])]
    public function order(
        Request $request,
        StatesRepository $statesRepository,
        CitiesRepository $citiesRepository,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        PaymentTypeRepository $paymentTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ProductsSalesPointsRepository $productSalesPointsRepository
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        // Crear arrays para almacenar los errores
        $errors = [];

        if (!@$data['name']) {
            $errors['name'] = 'El campo name es requerido';
        }
        if (!@$data['email']) {
            $errors['email'] = 'El campo email es requerido';
        }
        if (!@$data['identity_number']) {
            $errors['identity_number'] = 'El campo identity_number es requerido';
        }
        if (!@$data['state_id']) {
            $errors['state_id'] = 'El campo state_id es requerido';
        }
        if (!@$data['city_id']) {
            $errors['city_id'] = 'El campo city_id es requerido';
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
        if (!@$data['phone_number']) {
            $errors['phone_number'] = 'El campo phone_number es requerido';
        }
        if (!@$data['products']) {
            $errors['products'] = 'El campo products es requerido y tiene que ser un array de objetos de product_id y quantity';
        }
        if (!@$data['payment_type_id']) {
            $errors['payment_type_id'] = 'El campo payment_type_id es requerido';
        }


        $salesPoints = [];
        // Iterar sobre los productos enviados en la solicitud
        foreach ($data['products'] as $product_cart) {
            $productId = $product_cart['product_id'];
            $quantity = $product_cart['quantity'];

            // Verificar si el producto existe y está activo
            $product = $productSalesPointsRepository->findActiveProductById($productId);
            if (!$product) {
                $errors['product_not_found'][] = $productId;
                continue;
            }

            // Verificar si el producto está en el carrito de compras
            $product_on_cart = $shoppingCartRepository->findShoppingCartProductByStatus($productId, $this->customer->getId(), Constants::STATUS_SHOPPING_CART_ACTIVO);
            if (!$product_on_cart) {
                $product_on_cart = new ShoppingCart();
                $product_on_cart->setCustomer($this->customer)
                    ->setStatus($statusTypeShoppingCartRepository->find(Constants::STATUS_SHOPPING_CART_ACTIVO))
                    ->setProductsSalesPoints($product);
                $em->persist($product_on_cart);
            }
            // Verificar disponibilidad de cantidad
            if ((!@$product_on_cart->getProductsSalesPoints()->getLastInventory()) || $product_on_cart->getProductsSalesPoints()->getLastInventory()->getAvailable() < $quantity) {
                $errors['product_quantity_not_available'][] = $product_on_cart->getProductsSalesPoints()->getDataBasicProductFront();
            }
            $product_on_cart->setQuantity($quantity);

            // Agregar producto al carrito de compras
            $salesPoints[$product_on_cart->getProductsSalesPoints()->getProduct()->getSalePoint()->getId()][] = $product_on_cart;
        }

        // Verificar si hubo errores
        if (!empty($errors)) {
            $response = [
                "status" => false,
                'message' => 'Error al intentar agregar uno o más productos a la orden.',
                "errors" => $errors
            ];
            return $this->json($response, Response::HTTP_CONFLICT, ['Content-Type' => 'application/json']);
        }

        $orders = [];
        foreach ($salesPoints as $key => $shopping_cart_products) {
            $order = new Orders();

            $order
                ->setCustomer($this->customer)
                ->setCustomerName($data['name'])
                ->setCustomerEmail($data['email'])
                ->setCustomerIdentityNumber($data['identity_number'])
                ->setCodeAreaPhoneCustomer($data['code_area'])
                ->setPhoneCustomer($data['phone_number'])
                ->setCustomerState($statesRepository->find($data['state_id']))
                ->setCustomerCity($citiesRepository->find($data['city_id']))
                ->setCustomerPostalCode($data['postal_code'])
                ->setCustomerStreetAddress($data['street_address'])
                ->setCustomerNumberAddress($data['number_address'])
                ->setCustomerFloorApartment(@$data['floor_apartment'] ? $data['floor_apartment'] : null)
                ->setPaymentType($paymentTypeRepository->find($data['payment_type_id']))
                ->setStatus($statusOrderTypeRepository->findOneBy(["id" => Constants::STATUS_ORDER_OPEN]))
                ->setSalePoint($userRepository->find($key))
                ->setCreatedAt(new \DateTime());
            $total = 0;
            foreach ($shopping_cart_products as $shopping_cart_product) {
                $shopping_cart_product->setStatus($statusTypeShoppingCartRepository->findOneBy(["id" => Constants::STATUS_SHOPPING_CART_EN_ORDEN]));
                $order_product = new OrdersProducts();
                $order_product
                    ->setNumberOrder($order)
                    ->setName($shopping_cart_product->getProductsSalesPoints()->getProduct()->getName())
                    ->setName($shopping_cart_product->getProductsSalesPoints()->getProduct()->getName())
                    ->setCod($shopping_cart_product->getProductsSalesPoints()->getProduct()->getSalePoint()->getRole()->getId() == Constants::ROLE_SUPER_ADMIN ? 'A-' . $shopping_cart_product->getProductsSalesPoints()->getProduct()->getCod() : $shopping_cart_product->getProductsSalesPoints()->getProduct()->getSalePoint()->getId() . '-' . $shopping_cart_product->getProductsSalesPoints()->getProduct()->getCod())
                    ->setProductsSalesPoints($shopping_cart_product->getProductsSalesPoints())
                    ->setPrice($shopping_cart_product->getProductsSalesPoints()->getLastPrice()->getPrice())
                    ->setShoppingCart($shopping_cart_product)
                    ->setQuantity($shopping_cart_product->getQuantity());
                $em->persist($order_product);
                $em->persist($shopping_cart_product);
                $total = $total + ($shopping_cart_product->getQuantity() * $shopping_cart_product->getProductsSalesPoints()->getLastPrice()->getPrice());
            }
            $order->setTotalOrder($total);
            $em->persist($order);
            $orders[] = $order->generateOrder();
        }
        try {
            $em->flush();
            return $this->json(
                [
                    'status' => true,
                    'order' => $orders,
                    'message' => 'Orden creada correctamente.'
                ],
                Response::HTTP_CREATED,
                ['Content-Type' => 'application/json']
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route("/order/{order_id}", name: "api_customer_order_id", methods: ["GET", "POST"])]
    public function orderById(
        $order_id,
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        OrdersRepository $ordersRepository
        // CustomerAd
    ): Response {

        switch ($request->getMethod()) {
            case 'GET':
                $order = $ordersRepository->findOrderByCustomerId($this->customer->getId(), $order_id);
                $items = [];
                $bill_data = [
                    "bill_data" => [
                        "identity_type" => "DNI",
                        "identity_number" => "34987273",
                        "country_id" => 11,
                        "country_name" => "Argentina",
                        "state_id" => 4545,
                        "state_name" => "Buenos Aires",
                        "city_id" => 42022,
                        "city_name" => "Ciudad Autonoma de Buenos Aires",
                        "code_zip" => "abc123",
                        "additional_info" => "informacion adicional",
                        "address" => "Calle 123 4to A"
                    ]
                ];


                switch ($order->getStatus()->getId()) {
                    case Constants::STATUS_ORDER_PENDING:
                        foreach ($order->getOrdersProducts() as $order_product) {
                            $items[] = [
                                "id" => $order_product->getProduct()->getId(),
                                "name" => $order_product->getProduct()->getName(),
                                "quantity" => $order_product->getQuantity(),
                                "price" => number_format((float)$order_product->getProduct()->getPrice(), 2, ',', '.'),
                                "discount_price" => $order_product->getProduct()->getDiscountActive() ?  ($order_product->getProduct()->getPrice() - (($order_product->getProduct()->getPrice() / 100) * $order_product->getProduct()->getDiscountActive())) : 0,
                            ];
                        }
                        $order = [
                            'items' => $items
                        ];
                        break;
                    default:
                        dd('default case');
                        break;
                }


                return $this->json(
                    $order,
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
                /*
                {
                    "items": [
                        {
                            "id": 10,
                            "name": "Prueba producto 1",
                            "quantity": 5,
                            "price": 10,
                            "old_price":
                        },
                        {
                            "id": 11,
                            "name": "Prueba producto 2",
                            "quantity": 4,
                            "price": 20,
                            "old_price":
                        },
                        {
                            "id": 13,
                            "name": "Prueba producto 3",
                            "quantity": 1,
                            "price": 300,
                            "old_price":
                        }
                    ],
                    "bill_data": {
                        "identity_type": "DNI",
                        "identity_number": "34987273",
                        "country_id": 11,
                        "country_name": "Argentina",
                        "state_id": 4545,
                        "state_name": "Buenos Aires",
                        "city_id": 42022,
                        "city_name": "Ciudad Autonoma de Buenos Aires",
                        "code_zip" : "abc123",
                        "additional_info": "informacion adicional",
                        "address": "Calle 123 4to A"
                    },
                    "recipients": [
                        {
                            "recipient_id": 1,
                            "country_name": "Argentina",
                            "state_name": "Córdoba",
                            "city_name": "Cosquin",
                            "recipient_name": "Destinatario prueba 1",
                            "address": "Direccion destinatario 1 23233",
                            "recipient_phone": "1163549766"
                        },
                        {
                            "recipient_id": 2,
                            "country_name": "Argentina",
                            "state_name": "Córdoba",
                            "city_name": "La falda",
                            "recipient_name": "Destinatario prueba 2",
                            "address": "Direccion destinatario 2 23233",
                            "recipient_phone": "1163549766"
                        },
                        {
                            "recipient_id": 3,
                            "country_name": "Argentina",
                            "state_name": "Córdoba",
                            "city_name": "Córdoba Capital",
                            "recipient_name": "Destinatario prueba 3",
                            "address": "Direccion destinatario 3 23233",
                            "recipient_phone": "1163549766"
                        }
                    ]
                }
                */

                $order = $ordersRepository->find(['id' => $order_id]);

                return $this->json(
                    $order->generateOrder(),
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
            case 'PATCH':

                $body = $request->getContent();
                $data = json_decode($body, true);
        }


        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), 1);
        if (!$shopping_cart_products) {
            return $this->json(
                [
                    "shop_cart_list" => [],
                    'message' => 'No tiene productos en su lista de carrito.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route("/orders", name: "api_customer_orders", methods: ["GET"])]
    public function orders(
        $id,
        Request $request,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        ShoppingCartRepository $shoppingCartRepository,
        StatusTypeShoppingCartRepository $statusTypeShoppingCartRepository,
        EntityManagerInterface $em,
        OrdersRepository $ordersRepository
    ): Response {

        switch ($request->getMethod()) {
            case 'GET':

                $order = $ordersRepository->find(['id' => $id]);

                return $this->json(
                    $order->generateOrder(),
                    Response::HTTP_OK,
                    ['Content-Type' => 'application/json']
                );
            case 'PATCH':

                $body = $request->getContent();
                $data = json_decode($body, true);
        }


        $shopping_cart_products = $shoppingCartRepository->findAllShoppingCartProductsByStatus($this->customer->getId(), 1);
        if (!$shopping_cart_products) {
            return $this->json(
                [
                    "shop_cart_list" => [],
                    'message' => 'No tiene productos en su lista de carrito.'
                ],
                Response::HTTP_ACCEPTED,
                ['Content-Type' => 'application/json']
            );
        }
    }
}
