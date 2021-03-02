<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
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
     * @ORM\column(type="string",length=255)
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
     * @ORM\ManyToMany(targetEntity=Sortie::class)
     */
    private $inscritSortie;
    public function __construct()
    {
        $this->inscritSortie = new ArrayCollection();
        $this->organisateurSortie = new ArrayCollection();
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
    public function getInscritSortie()
    {
        return $this->inscritSortie;
    }

    /**
     * @param mixed $inscritSortie
     */
    public function setInscritSortie($inscritSortie): void
    {
        $this->inscritSortie = $inscritSortie;
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
    public function getCampus(): Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @param mixed $inscritSortie
     */
    public function inscritSortie($inscritSortie): void
    {
        $this->inscritSortie = $inscritSortie;
    }
    public function addInscritSortie(participant $inscritSortie): self
    {
    if (!$this->inscritSortie->contains($inscritSortie)) {
        $this->inscritSortie[] = $inscritSortie;
    }
    return $this;
}
    public function removeInscritSortie(participant $inscritSortie): self
    {
        $this->inscritSortie->removeElement($inscritSortie);
    return $this;
    }






}
