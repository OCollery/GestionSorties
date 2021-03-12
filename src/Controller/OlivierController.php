<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\UpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class OlivierController extends AbstractController
{
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
    public function publier(EntityManagerInterface $em, Sortie $sortie,int $id)
    {

        //permet de mettre à jour l'état en ouverte (publié)
        $etat = $em->getRepository(Etat::class)->find(2);
        $sortie->setEtat($etat);
        $em->flush();

        $this->addFlash('success','La sortie a été publiée');
        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/Modifier_Sortie{id}", name="modifier")
     */
    public function modifierSortie(EntityManagerInterface $em , Request $request,Sortie $sortie,UserInterface $user)
    {
        //on récupère le formulaire updateType
        $form = $this->createForm(UpdateType::class, $sortie);//$sortie va ds le form
        $form->handleRequest($request);//traite les infos

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash('success', 'la sortie a été mise à jour');
            return $this->redirectToRoute('home');
        }

        $idOrganisateur = $sortie->getOrganisateur()->getId();
        $userId = $user->getId();

        if($userId === $idOrganisateur)
        {
            return $this->render('olivier/modifierUneSortie.html.twig', [
                'sorties'=>$sortie,
                'updateForm'=>$form->createView()]);
        }else{
            $this->addFlash('error','Vous ne pouvez pas modifier cette sortie');
            return $this->redirectToRoute('home');
        }




    }

}


