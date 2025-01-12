<?php

namespace App\Entity;

use App\Repository\SauvegardeProfilRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SauvegardeProfilRepository::class)]
class SauvegardeProfil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSauvegarde = null;

    #[ORM\Column]
    private array $contenu = [];

    #[ORM\ManyToOne(inversedBy: 'sauvegardeProfils')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSauvegarde(): ?\DateTimeInterface
    {
        return $this->dateSauvegarde;
    }

    public function setDateSauvegarde(\DateTimeInterface $dateSauvegarde): static
    {
        $this->dateSauvegarde = $dateSauvegarde;

        return $this;
    }

    public function getContenu(): array
    {
        return $this->contenu;
    }

    public function setContenu(array $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
