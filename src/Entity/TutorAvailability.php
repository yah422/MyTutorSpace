<?php

namespace App\Entity;

use App\Repository\TutorAvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TutorAvailabilityRepository::class)]
class TutorAvailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'availabilities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $tuteur = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column]
    private bool $isBooked = false;

    // Getter pour id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter et Setter pour tuteur
    public function getTuteur(): ?User
    {
        return $this->tuteur;
    }

    public function setTuteur(?User $tuteur): self
    {
        $this->tuteur = $tuteur;

        return $this;
    }

    // Getter et Setter pour startTime
    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    // Getter et Setter pour endTime
    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    // Getter et Setter pour isBooked
    public function isBooked(): bool
    {
        return $this->isBooked;
    }

    public function setIsBooked(bool $isBooked): self
    {
        $this->isBooked = $isBooked;

        return $this;
    }
}
