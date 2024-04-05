<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/index")]
class DefaultController extends AbstractController
{
    #[Route("/", name: "app_homepage")]
    public function index(): Response
    {
        $data['files_js'] = array('apexcharts.init.js?v=' . rand());
        $data['title'] = 'Inicio';
        $data['controller_name'] = 'DefaultController';
        return $this->render('home/dashboard.html.twig', $data);
    }
}
