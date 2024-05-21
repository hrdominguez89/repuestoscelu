<?php

namespace App\Controller\Api\Customer;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Entity\OrdersProducts;
use App\Entity\PaymentsFiles;
use App\Entity\ProductSalePointInventory;
use App\Entity\ShoppingCart;
use App\Helpers\EnqueueEmail;
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
        EnqueueEmail $queue,
        ProductsSalesPointsRepository $productSalesPointsRepository,
        FileUploader $fileUploader
    ): Response {

        $body = $request->getContent();
        $data = json_decode($body, true);

        $orders = [];
        $ordersGenerated = [];
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
        if (!@$data['cel_phone']) {
            $errors['cel_phone'] = 'El campo cel_phone es requerido';
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
            $salesPoints[$product_on_cart->getProductsSalesPoints()->getSalePoint()->getId()][] = $product_on_cart;
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
        foreach ($salesPoints as $key => $shopping_cart_products) {
            $order = new Orders();

            $order
                ->setCustomer($this->customer)
                ->setCustomerName($data['name'])
                ->setCustomerEmail($data['email'])
                ->setCustomerIdentityNumber($data['identity_number'])
                ->setCodeAreaPhoneCustomer($data['code_area'])
                ->setPhoneCustomer($data['cel_phone'])
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
                    ->setOrderNumber($order)
                    ->setName($shopping_cart_product->getProductsSalesPoints()->getProduct()->getName())
                    ->setName($shopping_cart_product->getProductsSalesPoints()->getProduct()->getName())
                    ->setCod($shopping_cart_product->getProductsSalesPoints()->getProduct()->getSalePoint()->getRole()->getId() == Constants::ROLE_SUPER_ADMIN ? 'A-' . $shopping_cart_product->getProductsSalesPoints()->getProduct()->getCod() : $shopping_cart_product->getProductsSalesPoints()->getProduct()->getSalePoint()->getId() . '-' . $shopping_cart_product->getProductsSalesPoints()->getProduct()->getCod())
                    ->setProductsSalesPoints($shopping_cart_product->getProductsSalesPoints())
                    ->setPrice($shopping_cart_product->getProductsSalesPoints()->getLastPrice()->getPrice())
                    ->setShoppingCart($shopping_cart_product)
                    ->setQuantity($shopping_cart_product->getQuantity());
                $em->persist($order_product);
                $order->addOrdersProduct($order_product);
                $em->persist($shopping_cart_product);

                $inventory =  new ProductSalePointInventory();

                $inventory
                    ->setProductSalePoint($shopping_cart_product->getProductsSalesPoints())
                    ->setOnHand($shopping_cart_product->getProductsSalesPoints()->getLastInventory()->getOnHand())
                    ->setAvailable($shopping_cart_product->getProductsSalesPoints()->getLastInventory()->getAvailable() - $shopping_cart_product->getQuantity())
                    ->setCommitted($shopping_cart_product->getProductsSalesPoints()->getLastInventory()->getCommitted() + $shopping_cart_product->getQuantity())
                    ->setSold($shopping_cart_product->getProductsSalesPoints()->getLastInventory()->getSold());

                $em->persist($inventory);

                $total = $total + ($shopping_cart_product->getQuantity() * $shopping_cart_product->getProductsSalesPoints()->getLastPrice()->getPrice());
            }
            $order->setTotalOrder($total);



            $em->persist($order);
            $html = $this->renderView('secure/bill/bill.html.twig', [
                'order' => $order
            ]);

            $s3Path = $fileUploader->uploadPdf($html, 'sale_order', 'sale_order');
            $order->setBillFile($_ENV['AWS_S3_URL'] . $s3Path);
            $orders[] = $order->generateOrder();
            $ordersGenerated[] = $order;
        }

        try {
            $em->flush();



            //Intento enviar el correo encolado

            foreach ($ordersGenerated as $order) {

                $id_email = $queue->enqueue(
                    Constants::EMAIL_NEW_ORDER_CUSTOMER, //tipo de email
                    $this->customer->getEmail(), //email destinatario
                    [ //parametros
                        'name' => $this->customer->getName(),
                        'url_front_login' => $_ENV['FRONT_URL'],
                        'url_sale_order' => $order->getBillFile()
                    ]
                );

                $queue->sendEnqueue($id_email);

                $id_email = $queue->enqueue(
                    Constants::EMAIL_NEW_ORDER_SALE_POINT, //tipo de email
                    $order->getSalePoint->getSalePoint()->getEmail(), //email destinatario
                    [ //parametros
                        'sale_order_number' => $order->getId(),
                        'url_sale_order' => $order->getBillFile()
                    ]
                );

                $queue->sendEnqueue($id_email);
            }




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

    #[Route("/order/{order_id}", name: "api_customer_order_id", methods: ["GET", 'PATCH'])]
    public function orderById(
        $order_id,
        EntityManagerInterface $em,
        FileUploader $fileUploader,
        OrdersRepository $ordersRepository,
        Request $request,
        EnqueueEmail $queue
    ): Response {
        $order = $ordersRepository->findOrderByCustomerId($this->customer->getId(), $order_id);
        if (!$order) {
            return $this->json(
                [
                    'status' => false,
                    'message' => 'No se encontro la orden indicada'
                ],
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'application/json']
            );
        }

        switch ($request->getMethod()) {
            case 'GET':
                if (!$order->getBillFile()) {
                    $html = $this->renderView('secure/bill/bill.html.twig', [
                        'order' => $order
                    ]);

                    $s3Path = $fileUploader->uploadPdf($html, 'sale_order', 'sale_order');
                    $order->setBillFile($_ENV['AWS_S3_URL'] . $s3Path);
                    $em->persist($order);
                    $em->flush();
                }
                return $this->json(
                    [
                        'status' => true,
                        'order' => $order->generateOrder()
                    ],
                    Response::HTTP_ACCEPTED,
                    ['Content-Type' => 'application/json']
                );
            case 'PATCH':

                $body = $request->getContent();
                $data = json_decode($body, true);

                if (!isset($data['payment_file'])) {
                    return $this->json(
                        [
                            'status' => false,
                            'message' => 'No se proporcionó el archivo de pago'
                        ],
                        Response::HTTP_BAD_REQUEST,
                        ['Content-Type' => 'application/json']
                    );
                }

                $fileContent = base64_decode(explode('base64', $data['payment_file'])[1]);

                if ($fileContent === false) {
                    return $this->json(
                        [
                            'status' => false,
                            'message' => 'El archivo de pago proporcionado no es válido'
                        ],
                        Response::HTTP_BAD_REQUEST,
                        ['Content-Type' => 'application/json']
                    );
                }
                // Subir la imagen al bucket de S3
                try {
                    $path = $fileUploader->uploadBase64File($fileContent, 'payment_file', 'payments_files');

                    $paymentFile =  new PaymentsFiles();
                    $paymentFile->setPaymentFile($_ENV['AWS_S3_URL'] . $path)
                        ->setAmount((float)$data['amount'])
                        ->setDatePaid(new \DateTime($data['date_paid']))
                        ->setOrderNumber($order);
                    $em->persist($paymentFile);
                    $order->addPaymentsFile($paymentFile);
                    $em->flush();

                    $id_email = $queue->enqueue(
                        Constants::EMAIL_NEW_PAYMENT_FILE, //tipo de email
                        $order->getSalePoint()->getEmail(), //email destinatario
                        [ //parametros
                            'sale_order_number' => $order->getId(),
                        ]
                    );

                    //Intento enviar el correo encolado
                    $queue->sendEnqueue($id_email);
                } catch (\Exception $e) {
                    return $this->json(
                        [
                            'status' => false,
                            'message' => 'No se pudo guardar el archivo en S3'
                        ],
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        ['Content-Type' => 'application/json']
                    );
                }

                return $this->json(
                    [
                        'status' => true,
                        'order' => $order->generateOrder(),
                        'message' => 'Archivo de pago subido correctamente'
                    ],
                    Response::HTTP_ACCEPTED,
                    ['Content-Type' => 'application/json']
                );
        }
    }

    #[Route("/orders", name: "api_customer_orders", methods: ["GET"])]
    public function orders(
        FileUploader $fileUploader,
        OrdersRepository $ordersRepository,
        EntityManagerInterface $em
    ): Response {

        $orders = $ordersRepository->findOrdersByCustomerId($this->customer->getId());

        $ordersData = [];
        if ($orders) {
            foreach ($orders as $order) {
                if (!$order->getBillFile()) {
                    $html = $this->renderView('secure/bill/bill.html.twig', [
                        'order' => $order
                    ]);

                    $s3Path = $fileUploader->uploadPdf($html, 'sale_order', 'sale_order');
                    $order->setBillFile($_ENV['AWS_S3_URL'] . $s3Path);
                    $em->persist($order);
                }
                $ordersData[] = $order->generateOrder();
            }
            $em->flush();
        }

        return $this->json(
            [
                'status' => true,
                'orders' => $ordersData
            ],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
