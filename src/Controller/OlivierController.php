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
    public function MAJ()
    {

    }


    /**
     * @Route("/Modifier_Sortie{id}", name="modifier")
     */
    public function modifier(EntityManagerInterface $em , Request $request, Sortie $sortie): Response
    {
    //on récupère le formulaire
        $form = $this->createForm(UpdateType::class, $sortie);//$sortie va ds le form
        $form->handleRequest($request);//traite les infos

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash('success', 'la sortie a été mise à jour');
            return $this->redirectToRoute('home');
        }
        return $this->render('main/modifierUneSortie.html.twig', ['sorties'=>$sortie,'updateForm'=>$form->createView()]);

    }

}
