<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\CreerProfilType;
use App\Form\TelechargerProfilType;
use App\Form\VillesType;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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

        $this->addFlash('success', 'La ville a bien été enregistrée');
        }
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

        $this->addFlash('success', 'Le campus a bien été enregistré');
        }
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

    /**
     * @Route ("/creerProfil", name="creerProfil")
     */

    public function creerProfil (Request $request,
                                    UserPasswordEncoderInterface $passwordEncoder,
                                    EntityManagerInterface $em): Response
    {

        $user = new Participant();

        $form = $this->createForm(CreerProfilType::class,$user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashed= $passwordEncoder->encodePassword($user, $user->getMotPasse());
            $user->setMotPasse($hashed);

            $em->persist($user);
            $em->flush();


        }

        return $this->render('kg_user/creerProfil.html.twig', ["form" => $form->createView()]);
    }

    private function getDataFromFile(): array
    {
        $file=$this->dataDirectory.'participant.csv';
        $fileExtension=pathinfo($file,PATHINFO_EXTENSION);
        $normalizers=[new ObjectNormalizer()];

        $encoders=[
            new CsvEncoder(),
            new XmlEncoder(),
            new YamlEncoder(),
        ];
        $serializer=new Serializer($normalizers,$encoders);

        /** @var string $fileString */
        $fileString=file_get_contents($file);

        $data = $serializer->decode($fileString,$fileExtension);


    }

    private function importParticipants(UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $em):void
    {
        $usersCreated=0;
        foreach ($this->getDataFromFile() as $row){
            if(array_key_exists('pseudo',$row) && !empty($row['pseudo'])){
            $userRepo=$em->getRepository(Participant::class);
            $user=$userRepo->findOneBy([
                'pseudo'=>$row['pseudo']
            ]);
                if(!$user){
                    $user=new Participant();
                    $user->setPseudo($row['pseudo']);
                    $user->setNom(['nom']);
                    $user->setPrenom($row['prenom']);
                    $user->setTelephone($row['telephone']);
                    $user->setMail($row['mail']);
                    $user->setMotPasse($row['motPasse']);
                    $hashed= $passwordEncoder->encodePassword($user, $user->getMotPasse());
                    $user->setMotPasse($hashed);
                    $user->setAdmin($row['admin']);
                    $user->setActif($row['actif']);
                    $user->setCampus($row['campus']);

                $this->$em->persist($user);

                $usersCreated++;
                }
            }
        }
        $this->$em->flush();

        }
    /**
     * @Route ("/telechargerProfils", name="telechargerProfils")
     */

    public function essai (Request $request): Response
    {



        $form = $this->createForm(TelechargerProfilType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



        }


        return $this->render('kg_user/telechargerProfils.html.twig', ["form" => $form->createView()]);
    }

}
