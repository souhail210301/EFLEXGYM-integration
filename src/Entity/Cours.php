<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "cours")]
#[ORM\Entity(repositoryClass: App\Repository\CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le nom du cours ne peut pas être vide.")]
    private $nom;

    #[ORM\Column(name: "type", type: "string", length: 255, nullable: false)]
    #[Assert\Choice(choices: ["Presentiel", "En Ligne"], message: "Le type du cours doit être soit 'Presentiel' soit 'En Ligne'.")]
    private $type;

    #[ORM\Column(name: "duree", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La durée du cours ne peut pas être vide.")]
    private $duree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }
}
