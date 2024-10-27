<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 500)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "matieres")]
    private Collection $users;
    
    /**
     * @var Collection<int, Lecon>
     */
    #[ORM\OneToMany(targetEntity: Lecon::class, mappedBy: 'matiere')]
    private Collection $lecons;

    public function __construct()
    {
        $this->lecons = new ArrayCollection();
        $this->users = new ArrayCollection();
    }
    public function getUsers(): Collection
    {
        return $this->users;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
            $lecon->setMatiere($this);
        }

        return $this;
    }

    public function removeLecon(Lecon $lecon): static
    {
        if ($this->lecons->removeElement($lecon)) {
            // set the owning side to null (unless already changed)
            if ($lecon->getMatiere() === $this) {
                $lecon->setMatiere(null);
            }
        }

        return $this;
    }


}
