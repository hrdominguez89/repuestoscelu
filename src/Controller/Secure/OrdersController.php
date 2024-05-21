<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Entity\PaymentsFiles;
use App\Entity\ProductAdminInventory;
use App\Entity\ProductSalePointInventory;
use App\Form\OrderConfirmType;
use App\Form\PaymentFileType;
use App\Helpers\EnqueueEmail;
use App\Repository\OrdersRepository;
use App\Repository\StatusOrderTypeRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/orders")]
class OrdersController extends AbstractController
{
    #[Route("/", name: "secure_order_index")]
    public function index(OrdersRepository $ordersRepository, Request $request): Response
    {
        $data['user'] = $this->getUser();
        if ($data['user']->getRole()->getId() == Constants::ROLE_SUPER_ADMIN) {
            switch ($request->get('status')) {
                case 'all':
                    $data['orders'] = $ordersRepository->findAll();
                    $data['status'] = 'all';
                    break;
                case Constants::STATUS_ORDER_CONFIRMED:
                    $data['orders'] = $ordersRepository->findBy(['status' => Constants::STATUS_ORDER_CONFIRMED]);
                    $data['status'] = Constants::STATUS_ORDER_CONFIRMED;
                    break;
                case Constants::STATUS_ORDER_CANCELED:
                    $data['orders'] = $ordersRepository->findBy(['status' => Constants::STATUS_ORDER_CANCELED]);
                    $data['status'] = Constants::STATUS_ORDER_CANCELED;
                    break;
                case Constants::STATUS_ORDER_OPEN:
                default:
                    $data['orders'] = $ordersRepository->findBy(['status' => Constants::STATUS_ORDER_OPEN]);
                    $data['status'] = Constants::STATUS_ORDER_OPEN;
                    break;
            }
            $data['title'] = 'Ordenes';
        } else {
            $data['title'] = 'Ordenes';
            switch ($request->get('status')) {
                case 'all':
                    $data['orders'] = $ordersRepository->findAll();
                    $data['status'] = 'all';
                    break;
                case Constants::STATUS_ORDER_CONFIRMED:
                    $data['orders'] = $ordersRepository->findBy(['sale_point' => $data['user']->getId(), 'status' => Constants::STATUS_ORDER_CONFIRMED]);
                    $data['status'] = Constants::STATUS_ORDER_CONFIRMED;
                    break;
                case Constants::STATUS_ORDER_CANCELED:
                    $data['orders'] = $ordersRepository->findBy(['sale_point' => $data['user']->getId(), 'status' => Constants::STATUS_ORDER_CANCELED]);
                    $data['status'] = Constants::STATUS_ORDER_CANCELED;
                    break;
                case Constants::STATUS_ORDER_OPEN:
                default:
                    $data['orders'] = $ordersRepository->findBy(['sale_point' => $data['user']->getId(), 'status' => Constants::STATUS_ORDER_OPEN]);
                    $data['status'] = Constants::STATUS_ORDER_OPEN;
                    break;
            }
        }
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        return $this->renderForm('secure/orders/orders_list.html.twig', $data);
    }

    #[Route("/show/{order_id}", name: "secure_order_show")]
    public function show(
        OrdersRepository $ordersRepository,
        StatusOrderTypeRepository $statusOrderTypeRepository,
        EntityManagerInterface $em,
        Request $request,
        EnqueueEmail $queue,
        $order_id
    ): Response {
        $data['user'] = $this->getUser();

        if ($data['user']->getRole()->getId() == Constants::ROLE_SUPER_ADMIN) {
            $data['order'] = $ordersRepository->find($order_id);
        } else {
            $data['order'] = $ordersRepository->findOneBy(['id' => $order_id, 'sale_point' => $data['user']->getId()]);
        }

        if (!$data['order']) {
            $message['type'] = 'modal';
            $message['alert'] = 'danger';
            $message['title'] = 'No se encuentra la orden';
            $message['message'] = 'La orden indicada no existe.';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('secure_order_index');
        }

        $data['title'] = 'Ver orden';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_order_index', 'title' => 'Ordenes'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_simple.js?v=' . rand());
        if ($data['order']->getStatus()->getId() == Constants::STATUS_ORDER_OPEN) {
            $data['form'] = $this->createForm(OrderConfirmType::class, null, ["user" => $data['user']]);
            $data['form']->handleRequest($request);
            if ($data['form']->isSubmitted() && $data['form']->isValid()) {
                $data['order']->setStatus($statusOrderTypeRepository->find(($request->get('order_confirm')['status'])));
                $data['order']->setModifiedAt(new \DateTime());

                if ($request->get('order_confirm')['status'] == Constants::STATUS_ORDER_CONFIRMED) {
                    foreach ($data['order']->getOrdersProducts() as $product) {

                        $lastInventoryProductsSalesPoints = $product->getProductsSalesPoints()->getLastInventory();

                        $inventoryProductsSalesPoints =  new ProductSalePointInventory();
                        $inventoryProductsSalesPoints->setProductSalePoint($product->getProductsSalesPoints());
                        $inventoryProductsSalesPoints->setOnHand($lastInventoryProductsSalesPoints->getOnHand() - $product->getQuantity());
                        $inventoryProductsSalesPoints->setAvailable($lastInventoryProductsSalesPoints->getAvailable());
                        $inventoryProductsSalesPoints->setCommitted($lastInventoryProductsSalesPoints->getCommitted() - $product->getQuantity()); //commited porque es el reservado hasta que la orden de despacho se confirme o se cancele
                        $inventoryProductsSalesPoints->setSold($lastInventoryProductsSalesPoints->getSold() + $product->getQuantity());

                        $em->persist($inventoryProductsSalesPoints);

                        //Si el producto es admin tengo que sumar la venta al inventario de products admins
                        if ($product->getProductsSalesPoints()->getProduct()->getSalePoint()->getRole()->getId() == Constants::ROLE_SUPER_ADMIN) {
                            $lastInventoryAdmin = $product->getProductsSalesPoints()->getProduct()->getLastInventory();

                            $productSalePointInventory = new ProductAdminInventory();
                            $productSalePointInventory->setProduct($product->getProductsSalesPoints()->getProduct());
                            $productSalePointInventory->setOnHand($lastInventoryAdmin->getOnHand());
                            $productSalePointInventory->setAvailable($lastInventoryAdmin->getAvailable());
                            $productSalePointInventory->setCommitted($lastInventoryAdmin->getCommitted());
                            $productSalePointInventory->setSold($lastInventoryAdmin->getSold() + $product->getQuantity());
                            $productSalePointInventory->setDispatched($lastInventoryAdmin->getDispatched() - $product->getQuantity());
                            $em->persist($productSalePointInventory);
                        }
                    }
                } else { //canceled
                    foreach ($data['order']->getOrdersProducts() as $product) {

                        $lastInventoryProductsSalesPoints = $product->getProductsSalesPoints()->getLastInventory();

                        $inventoryProductsSalesPoints =  new ProductSalePointInventory();
                        $inventoryProductsSalesPoints->setProductSalePoint($product->getProductsSalesPoints());
                        $inventoryProductsSalesPoints->setOnHand($lastInventoryProductsSalesPoints->getOnHand());
                        $inventoryProductsSalesPoints->setAvailable($lastInventoryProductsSalesPoints->getAvailable() + $product->getQuantity());
                        $inventoryProductsSalesPoints->setCommitted($lastInventoryProductsSalesPoints->getCommitted() - $product->getQuantity()); //commited porque es el reservado hasta que la orden de despacho se confirme o se cancele
                        $inventoryProductsSalesPoints->setSold($lastInventoryProductsSalesPoints->getSold());

                        $em->persist($inventoryProductsSalesPoints);
                    }
                }

                $em->persist($data['order']);
                $em->flush();


                if ($request->get('order_confirm')['status'] == Constants::STATUS_ORDER_CONFIRMED) {
                    $id_email = $queue->enqueue(
                        Constants::EMAIL_ORDER_CONFIRMED_CUSTOMER, //tipo de email
                        $data['order']->getCustomer()->getEmail(), //email destinatario
                        [ //parametros
                            'name' => $data['order']->getCustomer()->getName(),
                            'sale_order_number' => $data['order']->getId(),
                        ]
                    );

                    //Intento enviar el correo encolado
                    $queue->sendEnqueue($id_email);
                } else {
                    $id_email = $queue->enqueue(
                        Constants::EMAIL_ORDER_CANCELED_CUSTOMER, //tipo de email
                        $data['order']->getCustomer()->getEmail(), //email destinatario
                        [ //parametros
                            'name' => $data['order']->getCustomer()->getName(),
                            'sale_order_number' => $data['order']->getId(),
                        ]
                    );

                    //Intento enviar el correo encolado
                    $queue->sendEnqueue($id_email);
                }

                $message['type'] = 'modal';
                $message['alert'] = 'success';
                $message['title'] = 'Cambios guardados';
                $message['message'] = 'La orden fue gestionada correctamente.';
                $this->addFlash('message', $message);
                return $this->redirectToRoute('secure_order_index');
            }
            return $this->renderForm('secure/orders/form_order_products_list.html.twig', $data);
        } else {
            return $this->renderForm('secure/orders/show_order_product_list.html.twig', $data);
        }
    }

    #[Route("/payment_file/{order_id}", name: "secure_order_payment_file")]
    public function paymentFile(
        OrdersRepository $ordersRepository,
        FileUploader $fileUploader,
        EntityManagerInterface $em,
        Request $request,
        $order_id
    ): Response {
        $data['user'] = $this->getUser();

        if ($data['user']->getRole()->getId() == Constants::ROLE_SUPER_ADMIN) {
            $data['order'] = $ordersRepository->find($order_id);
        } else {
            $data['order'] = $ordersRepository->findOneBy(['id' => $order_id, 'sale_point' => $data['user']->getId()]);
        }

        if (!$data['order']) {
            $message['type'] = 'modal';
            $message['alert'] = 'danger';
            $message['title'] = 'No se encuentra la orden';
            $message['message'] = 'La orden indicada no existe.';
            $this->addFlash('message', $message);
            return $this->redirectToRoute('secure_order_index');
        }

        $data['title'] = 'Cargar comprobante de pago';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_order_index', 'title' => 'Ordenes'),
            array("path" => "secure_order_show", "path_parameters" => ['order_id' => $order_id], "title" => "Ver order"),
            array('active' => true, 'title' => $data['title'])
        );

        $data['form'] = $this->createForm(PaymentFileType::class, null, ['order' => $data['order']]);
        $data['form']->handleRequest($request);
        if ($data['form']->isSubmitted() && $data['form']->isValid()) {
            $paymentFile = $data['form']->get('payment_file')->getData();
            if ($paymentFile) {
                // Upload file to AWS S3
                $fileName =  'payment_file';
                try {
                    $filePath = $fileUploader->upload($paymentFile, $fileName, 'payments_files', true);

                    $payment_file = new PaymentsFiles();
                    $payment_file->setPaymentFile($_ENV['AWS_S3_URL'] . $filePath)
                        ->setOrderNumber($data['order'])
                        ->setAmount($request->get('payment_file')['amount'])
                        ->setDatePaid(new \DateTime($request->get('payment_file')['date_paid']));
                    $em->persist($payment_file);
                    $data['order']->addPaymentsFile($payment_file);
                    $em->flush();

                    $message['type'] = 'modal';
                    $message['alert'] = 'success';
                    $message['title'] = 'Comprobante de pago';
                    $message['message'] = 'Se guardo el comprobante de pago correctamente.';
                    $this->addFlash('message', $message);
                } catch (Exception $e) {
                    $message['type'] = 'modal';
                    $message['alert'] = 'danger';
                    $message['title'] = 'Error';
                    $message['message'] = 'Error al guardar el comprobante de pago.';
                    $this->addFlash('message', $message);
                }
                return $this->redirectToRoute('secure_order_show', ['order_id' => $order_id]);
            }
        }
        return $this->renderForm('secure/orders/form_payment_file.html.twig', $data);
    }
}
