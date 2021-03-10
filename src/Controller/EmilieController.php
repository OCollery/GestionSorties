<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\VillesType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

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
    }//mettre un if pour si inscrit à la sortie je peux aller sur détail sortie



    /**
     * @Route ("/lieux", name="lieux")
     */
    public function gererLieux(LieuRepository $lieux, Request $request, EntityManagerInterface $manager)
    {
        $data = $request->request->get('search');
        $res = $lieux->findOneBySomeField($data);

        $lieu = new Lieu();

        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formLieu->handleRequest($request);

        if ($formLieu->isSubmitted() && $formLieu->isValid()) {
            $manager->persist($lieu);
            $manager->flush();

        $this->addFlash('success', 'Le lieu a bien été enregistré');
        }
        return $this->render('emilie/lieux.html.twig', [
            'formLieu' => $formLieu->createView(),
            'res' => $res
        ]);
    }



    /**
     * @Route ("deleteLieu/{id}", name="deleteLieu")
     */
    public function deleteLieu (Lieu $lieu, EntityManagerInterface $manager)
    {
        $manager->remove($lieu);
        $manager->flush();
        $this->addFlash('success', 'Le lieu a bien été supprimé');

        return $this->redirectToRoute('emilie/lieux.html.twig');
    }




    /**
     * @Route ("/sortie/{id}", name="sortie")
     * requirements={"id": "\d+"},
     * methods={"GET"}
     */
    public function showOuting($id, Request $request,Sortie $dateDebut)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if (empty($sortie)) {
            throw $this->createNotFoundException("Cette sortie n'existe pas");
        }

//        if (empty($participants)) {
//            throw $this->createNotFoundException("Il n'y a pas de participants");
//        }

        //essai de bloquer url si pas inscrit à la sortie


        //essai blocage affichage +30jours//écriture semble ok mais le if ne fonctionne pas correctement
        $aujourdhui = date('d/m/y');//date du jour en type string
        $dateSortie = $dateDebut->getDateHeureDebut();

        //$dateSortieEssai = date_modify($dateSortie,'+30 day');//fonctionne mais modifie l'affichage de la date dans le twig donc pb
        $dateSortieEssai = $dateSortie->format('d/m/y' );


      /*   echo ($dateSortieEssai);
        echo($aujourdhui);
        var_dump($aujourdhui);
        var_dump($dateSortieEssai);*/

        if ($aujourdhui < $dateSortieEssai)
        {
            return $this->render('emilie/afficheSortie.html.twig', [
                'sortie' => $sortie]);


        }else
            $this->addFlash('error','La sortie n\'est plus consultable');
        return  $this->redirectToRoute('home');
    }

}
