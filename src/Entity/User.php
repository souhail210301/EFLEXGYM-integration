<?php
// src/Entity/User.php

namespace App\Entity;

use App\Enum\RoleUser;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Enum\Gender;
use App\Enum\Role;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Please enter an email')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please enter a password')]
    #[Assert\Length(min: 6, minMessage: 'Your password should be at least {{ limit }} characters')]
    private ?string $motDePasse = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Please enter a name')]
    #[Assert\Regex(pattern: '/[A-Z]/', message: 'The name must contain at least one uppercase character')]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Please enter a last name')]
    #[Assert\Regex(pattern: '/[A-Z]/', message: 'The last name must contain at least one uppercase character')]
    private ?string $prenom = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateDeNaissance = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\NotBlank(message: 'Please choose a gender')]
    private ?string $sexe = null;



    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo = null;

    /**
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid image (jpeg, png, gif)",
     *     maxSizeMessage = "The file is too large ({{ size }} {{ suffix }}). Max {{ limit }} {{ suffix }} allowed."
     * )
     */
    private $photoFile;

    #[ORM\Column]

    private ?string $salaire = null;


    #[ORM\ManyToOne(targetEntity: BilanFinancier::class)]
    #[ORM\JoinColumn(name: 'id_bilan_financier', referencedColumnName: 'id')]
    private ?BilanFinancier $idBilanFinancier = null;




    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\NotBlank(message: 'Please choose a role')]
    private $role;

    // Other methods...

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        // Validate the gender value against the enum
        if (!RoleUser::isValid($role)) {
            throw new \InvalidArgumentException("Invalid role value: $role");
        }

        $this->role = $role;

        return $this;
    }

    public function getSalaire(): int
    {
        return $this->salaire;
    }

    public function setSalaire(int $salaire): void
    {
        $this->salaire = $salaire;
    }
    public function getIdBilanFinancier(): ?int
    {
        return $this->idBilanFinancier ? $this->idBilanFinancier->getId() : null;
    }

    public function setIdBilanFinancier(?BilanFinancier $idBilanFinancier): void
    {
        $this->idBilanFinancier = $idBilanFinancier;
    }




    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    public function setPhotoFile($photoFile): self
    {
        $this->photoFile = $photoFile;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
     

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    public function setPassword(string $password): static
    {
        $this->motDePasse = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->nom;
    }

    public function setName(?string $name): static
    {
        $this->nom = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->prenom;
    }

    public function setLastName(?string $lastName): static
    {
        $this->prenom = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->dateDeNaissance = $birthDate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->sexe;
    }

    public function setGender(?string $gender): static
    {
        // Validate the gender value against the enum
        if (!Gender::isValid($gender)) {
            throw new \InvalidArgumentException("Invalid gender value: $gender");
        }

        $this->sexe = $gender;

        return $this;
    }


}
