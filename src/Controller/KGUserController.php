<?php

namespace App\Controller;

use App\Form\MonProfilType;
use App\Form\ParticipantFileType;
use Doctrine\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class KGUserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login( AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('kg_user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error

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

    /**
     * @Route("/forgottenPassword", name="forgottenPassword")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ObjectManager $objectManager
     * @param $resetNotification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function forgottenPassword(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createFormBuilder()
            ->add('mail', EmailType::class)
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->getData('mail');
            var_dump($email);
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(Participant::class)
                ->findOneBy([
                    'mail' => $email
                ]);
            if (!$user) {
                $this->addFlash('warning', "Cet email n'existe pas.");
                return $this->redirectToRoute("mdp");
            } else {
                $user->setResetToken($this->generateToken());
                $em->persist($user);
                $em->flush();
               // $resetNotification->notify($user);/
            }
        }
        return $this->render('kg_user/forgottenPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
