<?php

namespace App\Controller\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 #[Route("/user")]
class UserController extends AbstractController
{
     #[Route("/", name:"user")]
    public function index(): Response
    {
        return $this->render('secure/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
