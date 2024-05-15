<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
#[ORM\Table(name: "exercice")]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter A Title For The Blog Post")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "The name must contain only alphabetic characters."
    )]    
    private $nom;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter A Description")]
    private $description;

    #[ORM\Column(name: "nbrDeSet", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Please Enter The Number of Sets")]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid integer.")]
    private $nbrdeset;

    #[ORM\Column(name: "groupeMusculaire", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter A Muscle Group")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "The name must contain only alphabetic characters."
    )]
    private $groupemusculaire;

    #[ORM\Column(name: "nbrDeRepetitions", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Please Enter The Number of Repetitions")]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid integer.")]
    private $nbrderepetitions;

    #[ORM\Column(name: "categorieExercice", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter A Category for the Exercise")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "The name must contain only alphabetic characters."
    )]
    private $categorieexercice;

    #[ORM\Column(name: "typeEquipement", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter Equipment Type")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "The name must contain only alphabetic characters."
    )]
    private $typeequipement;

    #[ORM\ManyToMany(targetEntity: "ProgrammePersonnalise", mappedBy: "exercices")]
    #[Assert\Count(min: 1, minMessage: "Please select at least one program.")]
    private $programmes;


    public function __construct()
    {
        $this->programmes = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNbrdeset(): ?int
    {
        return $this->nbrdeset;
    }

    public function setNbrdeset(int $nbrdeset): static
    {
        $this->nbrdeset = $nbrdeset;

        return $this;
    }

    public function getGroupemusculaire(): ?string
    {
        return $this->groupemusculaire;
    }

    public function setGroupemusculaire(string $groupemusculaire): static
    {
        $this->groupemusculaire = $groupemusculaire;

        return $this;
    }

    public function getNbrderepetitions(): ?int
    {
        return $this->nbrderepetitions;
    }

    public function setNbrderepetitions(int $nbrderepetitions): static
    {
        $this->nbrderepetitions = $nbrderepetitions;

        return $this;
    }

    public function getCategorieexercice(): ?string
    {
        return $this->categorieexercice;
    }

    public function setCategorieexercice(string $categorieexercice): static
    {
        $this->categorieexercice = $categorieexercice;

        return $this;
    }

    public function getTypeequipement(): ?string
    {
        return $this->typeequipement;
    }

    public function setTypeequipement(string $typeequipement): static
    {
        $this->typeequipement = $typeequipement;

        return $this;
    }
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }
    public function addProgramme(ProgrammePersonnalise $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->addExercice($this);
        }

        return $this;
    }

    public function removeProgramme(ProgrammePersonnalise $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            $programme->removeExercice($this);
        }

        return $this;
    }
}
