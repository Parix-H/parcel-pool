<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginFailController extends AbstractController
{
    /**
     * @Route("/login/fail", name="login_fail")
     */
    public function index()
    {
        return $this->render('login_fail/index.html.twig', [
            'controller_name' => 'LoginFailController',
        ]);
    }
}
