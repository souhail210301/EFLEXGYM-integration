<?php

namespace App\Entity;

use App\Repository\SuiviProgreRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SuiviProgreRepository::class)]
#[ORM\Table(name: "suivi_progre", indexes: [new Index(name: "fk_idUser", columns: ["idUser"])])]
class SuiviProgre
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Your Last Name")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "The name must contain only alphabetic characters."
    )]
    private $nom;

    #[ORM\Column(name: "prenom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Your First Name")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "The name must contain only alphabetic characters."
    )]
    private $prenom;

    #[ORM\Column(name: "age", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Your Age")]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid integer.")]
    private $age;

    #[ORM\Column(name: "taille", type: "float", precision: 10, scale: 0, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Your Height")]
    #[Assert\Type(type: "float", message: "The value {{ value }} is not a valid float.")]
    private $taille;

    #[ORM\Column(name: "poids", type: "float", precision: 10, scale: 0, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Your Weight")]
    #[Assert\Type(type: "float", message: "The value {{ value }} is not a valid float.")]
    private $poids;

    #[ORM\Column(name: "tour_de_taille", type: "float", precision: 10, scale: 0, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Your Waist Measurement")]
    #[Assert\Type(type: "float", message: "The value {{ value }} is not a valid float.")]
    private $tourDeTaille;

    #[ORM\Column(name: "sexe", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Specify Your Gender")]
    private $sexe;

    #[ORM\Column(name: "idUser", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Your User ID")]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid integer.")]
    private $iduser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTourDeTaille(): ?float
    {
        return $this->tourDeTaille;
    }

    public function setTourDeTaille(float $tourDeTaille): static
    {
        $this->tourDeTaille = $tourDeTaille;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }
}
