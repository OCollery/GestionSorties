<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\VillesType;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route ("/villes", name="villes")
     */
    public function gererVilles(VilleRepository $villes, Request $request, EntityManagerInterface $manager)
    {

        $data = $request->request->get('search');
        $res = $villes->findOneBySomeField($data);

        $ville = new Ville();

        $formVille = $this->createForm(VillesType::class, $ville);
        $formVille->handleRequest($request);

        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $manager->persist($ville);
            $manager->flush();
        }
        $this->addFlash('success', 'La ville a bien été enregistrée');

        return $this->render("admin/villes.html.twig", [
            'formVille' => $formVille->createView(),
            'res' => $res
        ]);
    }

    /**
     * @Route ("/campus", name="campus")
     */
    public function gererCampus(CampusRepository $campusList, Request $request, EntityManagerInterface $manager)
    {
        $data = $request->request->get('search');
        $res = $campusList->findOneBySomeField($data);

        $campus = new Campus();

        $formCampus = $this->createForm(CampusType::class, $campus);
        $formCampus->handleRequest($request);

        if ($formCampus->isSubmitted() && $formCampus->isValid()) {
                $manager->persist($campus);
                $manager->flush();
        }
        $this->addFlash('success', 'Le campus a bien été enregistré');
        return $this->render("admin/campus.html.twig", [
                'formCampus' => $formCampus->createView(),
                'res' => $res
            ]);
        }


    /**
     * @Route ("deleteCampus/{id}", name="deleteCampus")
     */
    public function deleteCampus (Campus $campus, EntityManagerInterface $manager)
    {
        $manager->remove($campus);
        $manager->flush();
        $this->addFlash('success', 'Le campus a bien été supprimé');

        return $this->redirectToRoute('admin_campus');
    }

    /**
     * @Route ("deleteVille/{id}", name="deleteVille")
     */
    public function deleteVille (Ville $ville, EntityManagerInterface $manager)
    {
        $manager->remove($ville);
        $manager->flush();
        $this->addFlash('success', 'La ville a bien été supprimée');

        return $this->redirectToRoute('admin_villes');
    }
}
