<?php

namespace App\Entity;

use App\Repository\ProgrammePersonnaliseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProgrammePersonnaliseRepository::class)]
#[ORM\Table(name: "programme_personnalise")]
class ProgrammePersonnalise
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "objectif", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Please Enter An Objectif ")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "The name must contain only alphabetic characters."
    )]
    private $objectif;

    #[ORM\ManyToMany(targetEntity: "Exercice", inversedBy: "programmes")]
    #[ORM\JoinTable(
        name: "programme_exercice",
        joinColumns: [new JoinColumn(name: "id_programme", referencedColumnName: "id")],
        inverseJoinColumns: [new JoinColumn(name: "id_exercice", referencedColumnName: "id")]
    )]
    private $exercices;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exercices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(string $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * @return Collection|Exercice[]
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices->add($exercice);
            $exercice->addProgramme($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercices->removeElement($exercice)) {
            $exercice->removeProgramme($this);
        }

        return $this;
    }
}
