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
use App\Repository\ParticipantRepository;
use App\Repository\VilleRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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
    private $dataDirectory;

    /**
     * @Route ("deleteParticipant/{id}", name="deleteParticipant")
     */
    public function deleteParticipant (Participant $users, EntityManagerInterface $manager)
    {
        $manager->remove($users);
        $manager->flush();
        $this->addFlash('success', 'L\'utilisateur a bien été supprimé');

        return $this->redirectToRoute('admin_gererUtilisateurs');
    }
    /**
     * @Route ("activer/{id}", name="activer")
     */
    public function activer (Participant $users, EntityManagerInterface $manager)
    {

        $users->setActif(true);

        $manager->flush();

        return $this->redirectToRoute('admin_gererUtilisateurs');
    }
    /**
     * @Route ("desactiver/{id}", name="desactiver")
     */
    public function desactiver (Participant $users, EntityManagerInterface $manager)
    {


        $users->setActif(false);

        $manager->flush();

        return $this->redirectToRoute('admin_gererUtilisateurs');
    }

    /**
     * @Route ("/gererUtilisateurs", name="gererUtilisateurs")
     */
    public function gererUtilisateurs(ParticipantRepository $users, Request $request, EntityManagerInterface $em): Response
    {

        $list = $users->findAll();




        return $this->render("admin/gererUtilisateurs.html.twig", [
            'list' => $list]);
    }

    private function getDataFromFile(): array
    {



        $file ='data/dataProfil.csv';
        $fileExtension = pathinfo($file,PATHINFO_EXTENSION);
        $normalizers = [new ObjectNormalizer()];

        $encoders=[
            new CsvEncoder(),
            new XmlEncoder(),
            new YamlEncoder(),
        ];
        $serializer=new Serializer($normalizers,$encoders);

        /** @var string $fileString */
        $fileString=file_get_contents($file);

        return $serializer->decode($fileString,$fileExtension);
    }


    /**
     * @Route ("/telechargerProfils", name="telechargerProfils")
     */

    public function essai (Request $request,FileUploader $fileUploader,UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $em): Response
    {
//recuperation du fichier dans le formulaire
        $form = $this->createForm(TelechargerProfilType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $dataFile */
            $dataFile = $form->get('Fichier')->getData();
            if ($dataFile)
            {
                $dataFileName = $fileUploader->upload($dataFile);
            }
//serialisation du fichier et injection dans l'entité Participant

            $usersCreated=0;



        //injection dans la bdd

            foreach ($this->getDataFromFile() as $row) {
                if (array_key_exists('pseudo', $row) && !empty($row['pseudo'])) {
                    $userRepo = $em->getRepository(Participant::class);
                    $user = $userRepo->findOneBy([
                        'pseudo' => $row['pseudo']
                    ]);
                    if (!$user) {
                        $user = new Participant();
                        $user->setPseudo($row['pseudo']);
                        $user->setNom($row['nom']);
                        $user->setPrenom($row['prenom']);
                        $user->setTelephone($row['telephone']);
                        $user->setMail($row['mail']);
                        $user->setMotPasse($row['motPasse']);
                        $hashed = $passwordEncoder->encodePassword($user, $user->getMotPasse());
                        $user->setMotPasse($hashed);
                        $user->setAdmin($row['admin']);
                        $user->setActif($row['actif']);

                        /**$campusRepo = $em->getRepository(Campus::class);
                        $campus = $campusRepo->findOneBy(['id' => $row['campus_id']]);

                        $user->setCampus('$campus');*/

                        $em->persist($user);
                        $em->flush();

                        $usersCreated++;
                    }
                }
            }


            /**$destination= $this->getParameter(('%kernel.project_dir').'/public/data');

            $uploadedFile->move($destination);*/




            $this->addFlash('success',$usersCreated.' profils ont été ajoutes sans affectation de campus');



        }


        return $this->render('kg_user/telechargerProfils.html.twig', ["form" => $form->createView()]);
    }

}
