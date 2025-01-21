<?php

namespace App\Entity;

use App\Repository\TutorAvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TutorAvailabilityRepository::class)]
class TutorAvailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['availability:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['availability:read'])]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column]
    #[Groups(['availability:read'])]
    private ?\DateTimeInterface $end = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'availabilities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['availability:read'])]
    private ?User $tuteur = null;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;
        return $this;
    }

    public function getTuteur(): ?User
    {
        return $this->tuteur;
    }

    public function setTuteur(?User $tuteur): self
    {
        $this->tuteur = $tuteur;
        return $this;
    }
}
