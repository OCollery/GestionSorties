<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmilieController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profil")
     * requirements={"id": "\d+"},
     * methods={"GET"}
     */
    public function showProfile($id, Request $request)
    {
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $participantRepo->find($id);

        if(empty($participant)) {
            throw $this->createNotFoundException("Ce participant n'existe pas");
        }

        return $this->render('emilie/index.html.twig', [
            'participant' => $participant
        ]);
    }

    /**
     * @Route ("/sortie/{id}", name="sortie")
     * requirements={"id": "\d+"},
     * methods={"GET"}
     */
    public function showOuting($id, Request $request)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participants = $participantRepo->findAll();

        if (empty($sortie)) {
            throw $this->createNotFoundException("Cette sortie n'existe pas");
        }

        if (empty($participants)) {
            throw $this->createNotFoundException("Ce participant n'existe pas");
        }

        return $this->render('emilie/afficheSortie.html.twig', [
            'sortie' => $sortie,
            'participants' => $participants
        ]);



    }

}
