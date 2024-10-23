<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'type')]
    private Collection $exercice;

    public function __construct()
    {
        $this->exercice = new ArrayCollection();
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
            $exercice->setType($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercice->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getType() === $this) {
                $exercice->setType(null);
            }
        }

        return $this;
    }

}
