<?php

namespace App\Controller;

use App\Form\MonProfilType;
use Psr\Container\ContainerInterface;
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
    public function login()
    {
        $this->addFlash('error','pseudo ou mot de passe incorrect');


        return $this->render('kg_user/login.html.twig', [

        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }



    /**
     * @Route("/monProfil", name="monProfil")
     */

    public function monProfil(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             EntityManagerInterface $em)
    {
        $user = $this->getUser();



        $form = $this->createForm(MonProfilType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashed= $passwordEncoder->encodePassword($user, $user->getMotPasse());
            $user->setMotPasse($hashed);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Le profil a été mis à jour');


        }

        return $this->render("kg_user/monProfil.html.twig", ["form" => $form->createView()]);
    }
    /**
     * @Route("/administrerProfil", name="administrerProfil")
     */

    public function administrerProfil(Request $request,
                                UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $em): Response
    {

        $user = new Participant();
        $user ->setActif(true);
        $user ->setAdmin(false);

        $form = $this->createForm(MonProfilType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashed= $passwordEncoder->encodePassword($user, $user->getMotPasse());
            $user->setMotPasse($hashed);

            $em->persist($user);
            $em->flush();


        }

        return $this->render("kg_user/administrerProfil.html.twig", ["form" => $form->createView()]);
    }


}
