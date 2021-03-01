<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
