<?php

namespace App\Controller;

use App\Entity\Etat;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\MotifType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MotifController extends AbstractController
{
    /**
     * @Route ("/Raison_annulation/{id}", name="raison_annulation")
     */
    public function afficherAnnulation(EntityManagerInterface $em,Request $request,Sortie $sortie,Etat $etat,
                                            int $id,UserInterface $user, Sortie $organisateur,
                                        Sortie $participants): Response
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


//test pas concluant
    //recup le participants du profil
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $participantRepo->find($id);//récup tous les participants de la table
        $idParticipant = $participant->getId();
       /* foreach ($participant as $value)
        {
            $iiiid = $value->getId();
            echo ('participants: '.$iiiid);
            echo ('<br>');

        }*/
        echo ('Afficher profil: '.$idParticipant);
        echo ('<br>');


//récup le userId
        $userId = $user -> getId();
        echo ('userId: '.$userId);
        echo ('<br>');
        //var_dump($participant);
        //var_dump($participantSortie);


    //récup toutes les sorties et leurs participants
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortieAll = $sortieRepo->findAll();
        foreach ($sortieAll as $value)
        {
            $compte=0;
            $sortieId = $value->getId();
            echo ('num sortie: '.$sortieId);
            echo('<br>');
            $participantsSortie = $value->getParticipants()->getValues();

            foreach ($participantsSortie as $value)
                {
                    $participantSortieId = $value->getId();
                    echo ('id des participants: '.$participantSortieId);
                    echo ('<br>');

                    if ($participantSortieId === $userId || $participantSortieId===$idParticipant)
                    {
                        $compte= $compte+1;
                        echo ('compte: '.$compte);
                        echo ('<br>');

                    }else{
                        echo ('user est pas là');
                        echo ('<br>');
                    }
                        if ($compte >=2)
                        {
                            echo ('je vais sur la bonne page');
                            echo ('<br>');
                        }
                }
        }
        //var_dump($sortieAll);















        //si l'id est différent de l'id connecté nous sommes renvoyé vers la page login
        $userId = $user -> getId();
        $organisateurSortie = $organisateur ->getOrganisateur()->getId();

        if ($userId === $organisateurSortie)
        {
            return $this->render('olivier/annulerSortie.html.twig',['sorties'=>$sortie,'motifForm'=>$formInfo->createView()]);
        }else{
            $this->addFlash('error','Vous ne pouvez pas annuler cette sortie');
            return $this->render('kg_user/login.html.twig');
        }

    }
}
