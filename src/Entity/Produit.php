<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: '')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    
    private ?string $image = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: '')]
    #[Assert\Type(type: 'float', message: '')]
    private ?float $prix = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: '')]
    #[Assert\Type(type: 'integer', message: '')]
    private ?int $quantite = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: '')]
    #[Assert\Length(min: 10, minMessage: '')]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "categorie", referencedColumnName: "id")]
    #[Assert\NotBlank(message: '')]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(targetEntity: BilanFinancier::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(name: 'id_bilan_financier', referencedColumnName: 'id')]
    private ?BilanFinancier $idBilanFinancier = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_admin = null;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }
    
    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }
    

    public function getIdBilanFinancier(): ?int
    {
        return $this->id_bilan_financier;
    }
    
    public function setIdBilanFinancier(?int $id_bilan_financier): self
    {
        $this->id_bilan_financier = $id_bilan_financier;
    
        return $this;
    }

    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }

    public function setIdAdmin(?int $id_admin): self
    {
        $this->id_admin = $id_admin;

        return $this;
    }

   }

      


