<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * ORM\Table(name="participant")
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */

class Participant implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

   /**
    * @ORM\column(type="string",length=255)
    */

    private $pseudo;

    /**
     * @ORM\column(type="string",length=255, unique=true)
     */

    private $nom;

    /**
     * @ORM\column(type="string",length=255)
     */

    private $prenom;

    /**
     * @ORM\column(type="string",length=255)
     */

    private $telephone;

    /**
     * @ORM\column(type="string",length=255, nullable=true)
     */

    private $mail;

    /**
     * @ORM\column(type="string",length=255)
     */

    private $motPasse;

    /**
     * @ORM\column(type="boolean")
     */

    private $admin;


    /**
     * @ORM\column(type="boolean")
     */

    private $actif;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus",inversedBy="participants")
     */

    private $campus;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Sortie", mappedBy="organisateur")
     */
    private $organisateurSortie;



    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participants")
     */
    private $sorties;

    /**
     * @var string|null
     * @ORM\column(type="string",length=255,nullable=true)
     */

    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="property_image",fileNameProperty="filename")
     */
    private $imageFile;



    public function __construct() {
        $this->sorties = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getOrganisateurSortie()
    {
        return $this->organisateurSortie;
    }

    /**
     * @param mixed $organisateurSortie
     */
    public function setOrganisateurSortie($organisateurSortie): void
    {
        $this->organisateurSortie = $organisateurSortie;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getMotPasse()
    {
        return $this->motPasse;
    }



    /**
     * @param mixed $motPasse
     */
    public function setMotPasse($motPasse): void
    {
        $this->motPasse = $motPasse;
    }

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin): void
    {
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif($actif): void
    {
        $this->actif = $actif;
    }

    /**
     * @return Campus
     */
    public function getCampus()
    {
       return $this->campus;

    }


    /**
     * @param Campus $campus
     */
    public function setCampus($campus): void
    {
        $this->campus = $campus;
    }


    public function getUsername(): string
    {
        return $this->pseudo;

    }

    public function getPassword(): ?string
    {
        return $this->motPasse;
    }

    public function getRoles()
    {
        $roles = array('ROLE_USER');

        if ($this->admin) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
    }

    public function getSalt()
    {

    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return ArrayCollection
     */
    public function getSorties(): ArrayCollection
    {
        return $this->sorties;
    }

    /**
     * @param ArrayCollection $sorties
     */
    public function setSorties(ArrayCollection $sorties): void
    {
        $this->sorties = $sorties;
    }

    /**
     * @param Sortie $sortie
     */
    public function addSortie(Sortie $sortie): void
    {
        // First we check if we already have this participant in our collection
        if ($this->sorties->contains($sortie)){
            // Do nothing if its already part of our collection
            return;
        }

        // Add participants to our array collection
        $this->sorties->add($sortie);

        // We also add this sortie to the participant. This way both entities are 'linked' together.
        // In a manyToMany relationship both entities need to know that they are linked together.
        $sortie->addParticipant($this);
    }

    /**
     * @param Sortie $sortie
     */
    public function removeSortie(Sortie $sortie): void
    {
        // First we check if we already have this participant in our collection
        if (!$this->sorties->contains($sortie)){
            // Do nothing if its not part of our collection
            return;
        }

        // Remove participants to our array collection
        $this->sorties->removeElement($sortie);

        // We also remove this sortie to the participant. This way both entities are 'linked' together.
        // In a manyToMany relationship both entities need to know that they are linked together.
        $sortie->removeParticipant($this);
    }


    public function serialize()
    {
        return serialize([
            $this->id,
            $this->pseudo,
            $this->motPasse,
            $this->mail,

        ]);
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->pseudo,
            $this->motPasse,
            $this->mail,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }


}
