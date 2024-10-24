<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\ManyToOne(inversedBy: 'ressource')]
    private ?Exercice $exercice = null;

    /**
     * @var Collection<int, Lien>
     */
    #[ORM\ManyToMany(targetEntity: Lien::class, inversedBy: 'ressources')]
    private Collection $lien;

    public function __construct()
    {
        $this->lien = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): static
    {
        $this->exercice = $exercice;

        return $this;
    }

    /**
     * @return Collection<int, Lien>
     */
    public function getLien(): Collection
    {
        return $this->lien;
    }

    public function addLien(Lien $lien): static
    {
        if (!$this->lien->contains($lien)) {
            $this->lien->add($lien);
        }

        return $this;
    }

    public function removeLien(Lien $lien): static
    {
        $this->lien->removeElement($lien);

        return $this;
    }

}
