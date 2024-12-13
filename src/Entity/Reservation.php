<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\GreaterThan("today", message: "La réservation doit être effectuée au moins 24 heures à l'avance.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le créneau horaire est obligatoire.")]
    private ?string $timeSlot = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'événement est obligatoire.")]
    private ?string $eventName = null;

    #[ORM\ManyToOne(inversedBy: 'userReservations')]
    private ?User $Relations = null;

    // Validation de la contrainte pour une plage horaire unique par date
    #[Assert\UniqueEntity(fields: ['date', 'timeSlot'], message: "Cette plage horaire est déjà réservée pour cette date.")]
    private $reservation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTimeSlot(): ?string
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(string $timeSlot): static
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getRelations(): ?User
    {
        return $this->Relations;
    }

    public function setRelations(?User $Relations): static
    {
        $this->Relations = $Relations;

        return $this;
    }
}

