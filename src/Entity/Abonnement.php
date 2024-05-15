<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\AbonnementRepository;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]

class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id= null;

  
    #[ORM\Column(length:150)]
    private ?string $type = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être un nombre strictement supérieur à zéro.')]
    private ?float $prix = null;

    #[ORM\Column(type: "datetime")]
    #[Assert\GreaterThan('today', message: 'La date de début doit être future à aujourd\'hui.')]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: "datetime")]
    #[Assert\Expression(
        "this.getDateFin() > this.getDateDebut()",
        message:"La date de fin doit être postérieure à la date de début."
    )]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length:150)]
    private ?string $etat=null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'abonnements')]
    #[ORM\JoinColumn(name: 'id_adherent', referencedColumnName: 'id')]
    private ?User $id_adherent = null;

    #[ORM\ManyToOne(targetEntity: BilanFinancier::class, inversedBy: 'abonnements')]
    #[ORM\JoinColumn(name: 'id_bilan_financier', referencedColumnName: 'id')]
    private ?BilanFinancier $idBilanFinancier = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdAdherent(): ?User
    {
        return $this->id_adherent;
    }

    public function setIdAdherent(?User $id_adherent): static
    {
        $this->id_adherent = $id_adherent;

        return $this;
    }

    public function getIdBilanFinancier(): ?BilanFinancier
    {
        return $this->idBilanFinancier;
    }

    public function setIdBilanFinancier(?BilanFinancier $idBilanFinancier): static
    {
        $this->idBilanFinancier = $idBilanFinancier;

        return $this;
    }


}
