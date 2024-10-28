<?php

namespace App\Entity;

use App\Repository\LeconRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Niveau::class, mappedBy: 'lecons')]
    private Collection $niveaux;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Matiere", inversedBy="lecons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lecons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    // Getter method for user
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Setter method for user
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    // Setter method for matiere
    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    // Getter method for dateCreation
    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    // Setter method for dateCreation
    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->addLecon($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            $niveau->removeLecon($this);
        }

        return $this;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="lecons")
     * @ORM\JoinTable(name="lecon_user")
     */
    private $users;

    // Getter for users
    public function getUsers(): Collection
    {
        return $this->users;
    }

    // Add a user to the lecon
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }
        return $this;
    }

    // Remove a user from the lecon
    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
        return $this;
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Niveau", inversedBy="lecons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    // Getter for niveau
    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    // Setter for niveau
    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;
        return $this;
    }
}
