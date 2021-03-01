<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KGUserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {

        return $this->render('user/login.html.twig', [

        ]);
    }
}
