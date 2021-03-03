<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
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
    public function modifier(EntityManagerInterface $em , Request $request, Sortie $sortie, Lieu $lieu, Ville $ville): Response
    {
        $repo = $em->getRepository(Lieu::class);


    //on récupère le formulaire
        $form = $this->createForm(UpdateType::class, $sortie);//$sortie va ds le form
        $form->handleRequest($request);//traite les infos

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash('success', 'la sortie a été mise à jour');
            return $this->redirectToRoute('home');
        }
        return $this->render('main/modifierUneSortie.html.twig', ['villes'=>$ville,'lieux'=>$lieu,'sorties'=>$sortie,'updateForm'=>$form->createView()]);

    }


    /**
     * @Route ("/delete{id}", name="delete_sortie")
     */
    public function delete(EntityManagerInterface  $em, Sortie $sortie)
    {
        $em->remove($sortie);
        $em->flush();

        $this->addFlash('success', 'La sortie a bien été supprimé');
        return $this->redirectToRoute('home');
    }

    public function publier()
    {

    }

    /**
     * @Route ("/annuler_sortie{id}", name="annuler")
     */
    public function annuler(EntityManagerInterface $em,Request $request,Sortie $sortie)
    {

        return $this->render('olivier/annulerSortie.html.twig',['sorties'=>$sortie]);
    }
}
