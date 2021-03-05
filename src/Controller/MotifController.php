<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\MotifType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MotifController extends AbstractController
{
    /**
     * @Route ("/Raison_annulation{id}", name="raison_annulation")
     */
    public function afficherAnnulationEssai(EntityManagerInterface $em,Request $request,Sortie $sortie, Etat $etat, int $id): Response
    {
        //on récupère le formulaire motifType
        $formInfo = $this->createForm(MotifType::class, $sortie);
        $formInfo->handleRequest($request);//traite les infos

        //permet de mettre à jour l'état en annulée
        $etat = $em ->getRepository(Etat::class)->find(6);
        $sortie ->setEtat($etat);

        if ($formInfo->isSubmitted() && $formInfo->isValid())
        {
            $em ->flush();

            $this->addFlash('success', 'La sortie a été annulée');
            return  $this->redirectToRoute('home');
        }
        return $this->render('olivier/annulerSortie.html.twig',['sorties'=>$sortie,'motifForm'=>$formInfo->createView()]);
    }


}
