<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Length(
        min: 2,
        max: 50,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas contenir plus de {{ limit }} caractères."
    )]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom = null;


    #[Assert\NotBlank(message: "L'e-mail ne peut pas être vide.")]
    #[Assert\Email(message: "L'e-mail '{{ value }}' n'est pas un e-mail valide.")]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    // #[Assert\NotBlank(message: 'Le sujet ne peut pas être vide')]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $sujet = null;


    #[Assert\NotBlank(message: "Le message ne peut pas être vide.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le message doit contenir au moins {{ limit }} caractères."
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;


    // #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    // private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    private ?User $user = null;

    // #[ORM\Column(length: 255)]
    // private ?string $prenom = null;

    // public function __construct()
    // {
    //     //initialise la date et l'heure du RDV lors de la création de l'objet
    //     $timezone = new \DateTimeZone('Europe/Paris');
    //     $this->createdAt = new \DateTime('now', $timezone);
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

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

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): static
    {
        $this->subject = $sujet;

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

    // public function getCreatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->createdAt;
    // }

    // public function setCreatedAt(\DateTimeImmutable $createdAt): static
    // {
    //     $this->createdAt = $createdAt;

    //     return $this;
    // }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // public function getPrenom(): ?string
    // {
    //     return $this->prenom;
    // }

    // public function setPrenom(string $prenom): static
    // {
    //     $this->prenom = $prenom;

    //     return $this;
    // }
}