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
        $ville = new Ville();

        $formVille = $this->createForm(VillesType::class, $ville);
        $formVille->handleRequest($request);

        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $manager->persist($ville);
            $manager->flush();
        }

        return $this->render("admin/villes.html.twig", [
            'formVille' => $formVille->createView(),
            'villes' => $villes->findAll()
        ]);
    }

    /**
     * @Route ("/campus", name="campus")
     */
    public function gererCampus(CampusRepository $campusList, Request $request, EntityManagerInterface $manager)
    {
        $campus = new Campus();

        $formCampus = $this->createForm(CampusType::class, $campus);
        $formCampus->handleRequest($request);

        if ($formCampus->isSubmitted() && $formCampus->isValid()) {
            $manager->persist($campus);
            $manager->flush();
        }

        return $this->render("admin/campus.html.twig", [
            'formCampus' => $formCampus->createView(),
            'campus' => $campusList->findAll()
        ]);
    }

}
