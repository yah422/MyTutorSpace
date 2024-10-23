<?php

namespace App\Entity;

use App\Repository\LeconRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeconRepository::class)]
class Lecon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 500)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'lecons')]
    private ?Matiere $matiere = null;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'lecon')]
    private Collection $exercice;

    public function __construct()
    {
        $this->exercice = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getExercice(): Collection
    {
        return $this->exercice;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercice->contains($exercice)) {
            $this->exercice->add($exercice);
            $exercice->setLecon($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercice->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getLecon() === $this) {
                $exercice->setLecon(null);
            }
        }

        return $this;
    }
}
