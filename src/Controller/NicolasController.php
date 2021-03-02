<?php

namespace App\Controller;

use App\Form\CreationSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NicolasController extends AbstractController
{
    /**
     * @Route("/creerSortie", name="creer_sortie")
     */
    public function creationSortie(Request $request, EntityManagerInterface $em)
    {
       // $this->denyAccessUnlessGranted("ROLE_USER");

        $sortie = new \App\Entity\Sortie();

        $createSortieForm = $this->createForm(CreationSortieType::class, $sortie);

        $createSortieForm->handleRequest($request);
        if($createSortieForm->isSubmitted() && $createSortieForm->isValid())
        {
            if($request->request->has('annuler'))
            {
                return $this->redirectToRoute('creer_sortie');
            }
            elseif($request->request->has('enregistrer'))
            {
                $etat = $em->getRepository(Etat::class)->find(1);
                $sortie->setIdEtat($etat);
            }
            elseif ($request->request->has('publier'))
            {
                $etat = $em->getRepository(Etat::class)->find(2);
                $sortie->setIdEtat($etat);
            }

            $em->persist($sortie);
            $em->flush();
        }

        return $this->render('nicolas/CreationSortie.html.twig', [
            'registerForm' => $createSortieForm->createView()
        ]);
    }
}
