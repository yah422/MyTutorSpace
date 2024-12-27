<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seance $seance = null;

    #[ORM\Column]
    private ?int $comprehension = null;

    #[ORM\Column]
    private ?int $participation = null;

    #[ORM\Column]
    private ?int $travail = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): self
    {
        $this->seance = $seance;

        return $this;
    }

    public function getComprehension(): ?int
    {
        return $this->comprehension;
    }

    public function setComprehension(?int $comprehension): self
    {
        $this->comprehension = $comprehension;

        return $this;
    }

    public function getParticipation(): ?int
    {
        return $this->participation;
    }

    public function setParticipation(?int $participation): self
    {
        $this->participation = $participation;

        return $this;
    }

    public function getTravail(): ?int
    {
        return $this->travail;
    }

    public function setTravail(?int $travail): self
    {
        $this->travail = $travail;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
