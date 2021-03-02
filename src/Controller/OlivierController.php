<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OlivierController extends AbstractController
{
    /**
     * @Route("/Modifier_Sortie", name="modifier")
     */
    public function modifier(): Response
    {
        return $this->render('main/modifierUneSortie.html.twig');
    }

    public function update(EntityManagerInterface $em, Request $request)
    {

    }
}
