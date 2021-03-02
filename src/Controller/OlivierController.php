<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\UpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OlivierController extends AbstractController
{
    /**
     * @Route("/Modifier_Sortie{id}", name="modifier")
     */
    public function modifier(EntityManagerInterface $em, Request $request, Sortie $sortie): Response
    {
    //Permet de récupérer l'ensemble des sorties
        $repo = $em->getRepository(Sortie::class);
        $sortie = $repo ->findAll();

    //on récupère le formulaire
      /*  $form = $this->createForm(UpdateType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           $em->flush();

           $this->addFlash('success', 'La sortie a été mise à jour');
           return $this->redirectToRoute('home');
        }*/
        $formView = $form->createView();

        return $this->render('main/modifierUneSortie.html.twig',[array('sortie'=>$sortie, 'updateForm'=>$formView)]);
    }

}
