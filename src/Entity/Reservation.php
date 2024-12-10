<?php

namespace App\Entity;

use DateTime;
use App\Entity\Lecon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[UniqueEntity(fields: ["dateDebut", "dateFin"], message: "Ce créneau horaire est déjà pris.")]
#[ORM\UniqueConstraint(name: "unique_reservation", columns: ["date_debut", "date_fin"])]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le prénom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le prénom ne peut pas contenir plus de {{ limit }} caractères."
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas contenir plus de {{ limit }} caractères."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'e-mail ne peut pas être vide.")]
    #[Assert\Email(message: "L'e-mail '{{ value }}' n'est pas un e-mail valide.")]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le message ne peut pas être vide.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le message doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $eleve = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\When(
        expression: 'this.getDateFin() != null', // Vérifie si la date de fin est renseignée
        constraints: [
            new Assert\LessThan(
                propertyPath: 'dateFin',
                message: 'La date de fin doit se situer après la date de début !'
            )
        ]
    )]
    #[Assert\NotBlank(message: 'Veuillez sélectionner une date de début')]
    #[Assert\GreaterThanOrEqual(
        "today",
        message: "Veuillez sélectionner une date dans le présent !"
    )]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $statut = 'en_attente';

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateCreation;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;
    
    #[ORM\ManyToOne(targetEntity: Lecon::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lecon $lecon = null;

    #[ORM\ManyToMany(targetEntity: Lecon::class, inversedBy: 'reservations')]
    private Collection $lecons;

    public function __construct()
    {
        $this->dateCreation = new DateTime();
        $this->lecons = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;
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

    public function getLecon(): ?Lecon
    {
        return $this->lecon;
    }

    public function setLecon(?Lecon $lecon): static
    {
        $this->lecon = $lecon;
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
        }
        return $this;
    }

    public function removeLecon(Lecon $lecon): static
    {
        $this->lecons->removeElement($lecon);
        return $this;
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateCreation(): \DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function __toString(): string
    {
        return $this->dateDebut->format('d/m/Y H:i') . ' - ' . $this->dateFin->format('d/m/Y H:i');
    }
}
