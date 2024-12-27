<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $eleve = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $tuteur = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'text')]
    private ?string $compte_rendu = null;

    #[ORM\Column(type: 'text')]
    private ?string $objectifs = null;

    #[ORM\Column(type: 'text')]
    private ?string $travail_maison = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matiere $matiere = null;

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEleve(): ?User
    {
        return $this->eleve;
    }

    public function setEleve(?User $eleve): self
    {
        $this->eleve = $eleve;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCompteRendu(): ?string
    {
        return $this->compte_rendu;
    }

    public function setCompteRendu(?string $compte_rendu): self
    {
        $this->compte_rendu = $compte_rendu;

        return $this;
    }

    public function getObjectifs(): ?string
    {
        return $this->objectifs;
    }

    public function setObjectifs(?string $objectifs): self
    {
        $this->objectifs = $objectifs;

        return $this;
    }

    public function getTravailMaison(): ?string
    {
        return $this->travail_maison;
    }

    public function setTravailMaison(?string $travail_maison): self
    {
        $this->travail_maison = $travail_maison;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }
}
