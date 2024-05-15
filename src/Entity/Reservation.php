<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_reservation", type: "integer")]
    private ?int $idReservation = null;

    #[ORM\Column(name: "id_planing", type: "integer")]
    private int $idPlaning;

    #[ORM\Column(name: "num_tell", type: "string", length: 255)]
    private string $numTell;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id")]
    private ?User $idUser = null;

    public function getIdReservation(): ?int
    {
        return $this->idReservation;
    }

    public function getIdPlaning(): int
    {
        return $this->idPlaning;
    }

    public function setIdPlaning(int $idPlaning): static
    {
        $this->idPlaning = $idPlaning;
        return $this;
    }

    public function getNumTell(): string
    {
        return $this->numTell;
    }

    public function setNumTell(string $numTell): static
    {
        $this->numTell = $numTell;
        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;
        return $this;
    }
}