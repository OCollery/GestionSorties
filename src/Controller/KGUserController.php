<?php

namespace App\Controller;

use App\Form\MonProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class KGUserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {

        return $this->render('kg_user/login.html.twig', [

        ]);
    }

    /**
     * @Route("/monProfil", name="monProfil")
     */

    public function monProfil(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             EntityManagerInterface $em)
    {
        $user = new Participant();
        $user ->setActif(true);
        $user ->setAdmin(false);

        $form = $this->createForm(MonProfilType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();


        }

        return $this->render("kg_user/monProfil.html.twig", ["form" => $form->createView()]);
    }
}
