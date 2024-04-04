<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


#[Route("/security")]
class SecurityController extends AbstractController
{
    #[Route("/login", name: "app_security_login", methods: ["GET", "POST"])]
    public function login(AuthenticationUtils $helper): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }
        return $this->render(
            'security/login.html.twig',
            [
                // last username entered by the user (if any)
                'last_username' => $helper->getLastUsername(),
                // last authentication error (if any)
                'error' => $helper->getLastAuthenticationError(),
                'active' => 'security',
            ]
        );
    }

    #[Route("/logout", name: "app_security_logout")]
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }
}
