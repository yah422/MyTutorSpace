<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NiveauRepository::class)]
class Niveau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Lecon>
     */
    #[ORM\ManyToMany(targetEntity: Lecon::class, mappedBy: 'niveau')]
    private Collection $lecons;

    /**
     * @var Collection<int, Lecon>
     */
    #[ORM\ManyToMany(targetEntity: Lecon::class, inversedBy: 'niveaux')]
    private Collection $lecon;

    public function __construct()
    {
        $this->lecons = new ArrayCollection();
        $this->lecon = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Lecon>
     */
    public function getLecons(): Collection
    {
        return $this->lecons;
    }

    public function addLecon(Lecon $lecon): static
    {
        if (!$this->lecons->contains($lecon)) {
            $this->lecons->add($lecon);
            $lecon->addNiveau($this);
        }

        return $this;
    }

    public function removeLecon(Lecon $lecon): static
    {
        if ($this->lecons->removeElement($lecon)) {
            $lecon->removeNiveau($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Lecon>
     */
    public function getLecon(): Collection
    {
        return $this->lecon;
    }
}
