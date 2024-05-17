<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Orders;
use App\Repository\OrdersRepository;
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
            $data['title'] = 'Productos en camino';
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
}
