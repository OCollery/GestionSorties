<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CreationSortieType;
use App\Form\RechercheSortieType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class NicolasController extends AbstractController
{
    /**
     * @Route("/creerSortie", name="creer_sortie")
     */
    public function creationSortie(Request $request, EntityManagerInterface $em,UserInterface $user)
    {
      //  $this->denyAccessUnlessGranted("ROLE_USER");

        $sortie = new Sortie();
       $sortie-> setOrganisateur($user);
        $createSortieForm = $this->createForm(CreationSortieType::class, $sortie);

        $createSortieForm->handleRequest($request);
        if ($createSortieForm->isSubmitted() && $createSortieForm->isValid()) {
            if ($request->request->has('annuler')) {
                return $this->redirectToRoute('creer_sortie');
            } elseif ($request->request->has('enregistrer')) {
                $etat = $em->getRepository(Etat::class)->find(1);
                $sortie->setEtat($etat);
            } elseif ($request->request->has('publier')) {
                $etat = $em->getRepository(Etat::class)->find(2);
                $sortie->setEtat($etat);
            }

            $em->persist($sortie);
            $em->flush();
        }

        return $this->render('nicolas/CreationSortie.html.twig', [
            'registerForm' => $createSortieForm->createView()
        ]);
    }

    /**
     * @Route("/rechercherSortie", name="recherche_sortie")
     */
    public function rechercheSortie(Request $request, EntityManagerInterface $em, UserInterface $user)
    {
        // $this->denyAccessUnlessGranted("ROLE_USER");
        // $userId = $user->getId(); récupération de l'id utilisateur

        $sortie = new Sortie();
        $rechercheSortieForm = $this->createForm(RechercheSortieType::class, $sortie);

        $rechercheSortieForm->handleRequest($request);
        if ($rechercheSortieForm->isSubmitted() && $rechercheSortieForm->isValid()) {

            $em->persist($sortie);
            $em->flush();
        }

        return $this->render('home.html.twig', [
            'registerForm' => $rechercheSortieForm->createView()
        ]);
    }

    /**
     * @Route("/home", name="sortie_list")
     */
    public function listeSortie()
    {
        //récupére les series en bdd

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->findSortie();
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $participantRepo->findAll();
        return $this->render('/home.html.twig', [
            "sorties" => $sortie,
            "participant"=> $participant
        ]);
    }

    /**
     * @Route ("/sortie/{id}", name="sortie")
     * requirements={"id": "\d+"},
     * methods={"GET"}
     */
    public function recupIdSortie($id, Request $request)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if (empty($sortie)) {
            throw $this->createNotFoundException("Cette sortie n'existe pas");
        }

//        if (empty($participants)) {
//            throw $this->createNotFoundException("Il n'y a pas de participants");
//        }

        return $this->render('/home', [
            'sortie' => $sortie
        ]);
    }
}
