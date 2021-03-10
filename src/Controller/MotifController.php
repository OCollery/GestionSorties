<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\MotifType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MotifController extends AbstractController
{
    /**
     * @Route ("/Raison_annulation{id}", name="raison_annulation")
     */
    public function afficherAnnulation(EntityManagerInterface $em,Request $request,Sortie $sortie,Etat $etat,
                                            int $id,UserInterface $user, Sortie $organisateur, Sortie $dateLimiteInscription): Response
    {
        //on récupère le formulaire motifType
        $formInfo = $this->createForm(MotifType::class, $sortie);
        $formInfo->handleRequest($request);//traite les infos

        //permet de mettre à jour l'état en annulée
        //$etat = $em ->getRepository(Etat::class)->find(6);
        //$sortie ->setEtat($etat);

        if ($formInfo->isSubmitted() && $formInfo->isValid())
        {
            $etat = $em ->getRepository(Etat::class)->find(6);
            $sortie ->setEtat($etat);

            $em ->flush();

            $this->addFlash('success', 'La sortie a été annulée');
            return  $this->redirectToRoute('home');
        }


    //si l'id est différent de l'id connecté nous sommes renvoyé vers la page login
        $userId = $user -> getId();
        echo ('User Id: '.$userId);
        $organisateurSortie = $organisateur ->getOrganisateur()->getId();

        if ($userId === $organisateurSortie)
        {
            return $this->render('olivier/annulerSortie.html.twig',['sorties'=>$sortie,'motifForm'=>$formInfo->createView()]);
        }else{
            $this->addFlash('error','Vous ne pouvez pas annuler cette sortie');
            return $this->render('kg_user/login.html.twig');
        }
        //return $this->render('olivier/annulerSortie.html.twig',['sorties'=>$sortie,'motifForm'=>$formInfo->createView()]);
    }
}
