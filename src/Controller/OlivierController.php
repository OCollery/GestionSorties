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

    /**
     * @Route ("/publication{id}",name="publier")
     */
    public function publier(EntityManagerInterface $em, Request $request,Etat $etat, Sortie $sortie,int $id)
    {
        //permet de mettre à jour l'état en ouverte (publié)
        $etat = $em->getRepository(Etat::class)->find(2);
        $sortie->setEtat($etat);
        $em->flush();

        $this->addFlash('success','La sortie a été publiée');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route ("/Raison_annulation{id}", name="raison_annulation")
     */
    public function afficherAnnulation(EntityManagerInterface $em,Request $request,Sortie $sortie)
    {
        //faire appel à l'input 'info' du formulaire de modif sortie
        $form = $this->createForm(UpdateType::class);
        $form->handleRequest($request);//traite les infos

        return $this->render('olivier/annulerSortie.html.twig',['updateForm'=>$form->createView(),'sorties'=>$sortie]);
    }

    /**
     * @Route ("/annuler_sortie{id}", name="annuler")
     */
    public function annuler(EntityManagerInterface $em,Request $request,Sortie $sortie, int $id, Etat $etat)
    {
        //permet de mettre à jour l'état en annulée
        $etat = $em ->getRepository(Etat::class)->find(6);
        $sortie ->setEtat($etat);

        $em ->flush();

        //permet de faire apparaitre un message de réussite et redirigé vers la page home
        $this->addFlash('success','La sortie a été annulée');
        return $this->redirectToRoute('home');
    }
}
