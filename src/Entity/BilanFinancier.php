<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BilanFinancierRepository;


#[ORM\Entity(repositoryClass: BilanFinancierRepository::class)]
class BilanFinancier
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy: "AUTO")]
    #[ORM\Column]
    private ?int $id= null;

    #[ORM\Column(type:"datetime")]
    private ?\DateTimeInterface $dateDebut=null;

    #[ORM\Column(type: "datetime")]
    #[Assert\Expression(
        "this.getDateFin() > this.getDateDebut()",
        message:"La date de fin doit être postérieure à la date de début."
    )]
    private ?\DateTimeInterface $dateFin=null;

    #[ORM\Column]
    private ?float $salairesCoachs=null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être un nombre strictement supérieur à zéro.')]
    private ?float $prixLocation=null;

    #[ORM\Column]
    private ?float $revenusAbonnements=null;

    #[ORM\Column]
    private ?float $revenusProduits=null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être un nombre strictement supérieur à zéro.')]
    private ?float $depenses=null;

    #[ORM\Column]
    private ?float $profit=null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(string $id): static
{
    $this->id = $id;

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
    public function getSalairesCoachs(): ?float
    {
        return $this->salairesCoachs;
    }

    public function setSalairesCoachs(float $salairesCoachs): static
    {
        $this->salairesCoachs = $salairesCoachs;

        return $this;
    }
    public function __toString()
{
    return (string) $this->getId(); // Assuming getId() returns the identifier for the BilanFinancier entity
}


    public function getPrixLocation(): ?float
    {
        return $this->prixLocation;
    }

    public function setPrixLocation(float $prixLocation): static
    {
        $this->prixLocation = $prixLocation;

        return $this;
    }

    public function getRevenusAbonnements(): ?float
    {
        return $this->revenusAbonnements;
    }

    public function setRevenusAbonnements(float $revenusAbonnements): static
    {
        $this->revenusAbonnements = $revenusAbonnements;

        return $this;
    }

    public function getRevenusProduits(): ?float
    {
        return $this->revenusProduits;
    }

    public function setRevenusProduits(float $revenusProduits): static
    {
        $this->revenusProduits = $revenusProduits;

        return $this;
    }

    public function getDepenses(): ?float
    {
        return $this->depenses;
    }

    public function setDepenses(float $depenses): static
    {
        $this->depenses = $depenses;

        return $this;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): static
    {
        $this->profit = $profit;

        return $this;
    }
    
    
    
    
    
    
}

