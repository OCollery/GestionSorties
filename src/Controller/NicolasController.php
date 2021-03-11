<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CreationSortieType;
use App\Form\RechercheSortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
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
     * @Route("/home", name="recherche_sortie")
     */
    public function rechercheSortie(Request $request, EntityManagerInterface $em, UserInterface $user, Campus $campusSortie)
    {
        // $this->denyAccessUnlessGranted("ROLE_USER");

        $rechercheSortieForm = $this->createForm(RechercheSortieType::class);

        $rechercheSortieForm->handleRequest($request);

        //récupére les series en bdd

        $campusSortie = $request->get('campus',"Campus Kemper");
        $campusRepo = $this->getDoctrine()->getRepository(Campus::class);
        $campus = $campusRepo->find($campusSortie)->getId();
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->findSortie($campus /*, $etat, $nom */);
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $participantRepo->findAll();

        return $this->render('/home.html.twig', [
            "sorties" => $sortie,
            "participant"=> $participant,
            'Form' => $rechercheSortieForm->createView()
             ]);
    }
    /**
     * @Route("/inscriptionSortie/{idSortie}", name="inscription_sortie")
     */
    public function inscriptionSortie(Request $request, EntityManagerInterface $em, SortieRepository $sortieRepo)
    {
        //  $this->denyAccessUnlessGranted("ROLE_USER");
        $idAAjouter=$request->get('idSortie');
        $participant=$this->getUser();
        $sortie=$sortieRepo->find($idAAjouter);
        $participant->addSortie($sortie);
        $sortie->addParticipant($participant);


        $aujourdhui = date('d/m/y');//date du jour en type string
        $cloture = $sortie->getDateLimiteInscription();//je reprend $sortie qui récupère déjà la sortie
        $clotureInscription = $cloture->format('d/m/y');

        if($aujourdhui <= $clotureInscription)
        {
            $em->persist($participant);
            $em->flush();

            $this->addFlash('success','Vous êtes bien inscrit à la sortie');
            return $this->redirectToRoute('home');
       }else{
            $this->addFlash('error','Vous ne pouvez plus vous inscrire à cette sortie');
            return $this->redirectToRoute('home');
       }


        //return $this->redirectToRoute('home');
    }

    /**
     * @Route("/desistementSortie/{idSortie}", name="desistement_sortie")
     */
    public function desistementSortie(Request $request, EntityManagerInterface $em,SortieRepository $sortieRepo)
    {
        //  $this->denyAccessUnlessGranted("ROLE_USER");
        $idAAjouter=$request->get('idSortie');
        $participant=$this->getUser();
        $sortie=$sortieRepo->find($idAAjouter);
        $participant->removeSortie($sortie);
        $sortie->removeParticipant($participant);
        $em->flush();

        return $this->redirectToRoute('home');
    }
}
