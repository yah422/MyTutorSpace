<?php

namespace App\Entity;

use DateTime;
use App\Entity\User;
use DateTimeInterface;
use App\Entity\Matiere;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TutoringBookingRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TutoringBookingRepository::class)]
class TutoringBooking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $studentName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $studentEmail = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matiere $matiere = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tutoringBookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $tuteur = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTimeInterface $preferredDate = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Choice(['morning', 'afternoon', 'evening'])]
    private ?string $preferredTimeSlot = null;

    #[ORM\Column(length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    // Getters and setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentName(): ?string
    {
        return $this->studentName;
    }

    public function setStudentName(string $studentName): self
    {
        $this->studentName = $studentName;
        return $this;
    }

    public function getStudentEmail(): ?string
    {
        return $this->studentEmail;
    }

    public function setStudentEmail(string $studentEmail): self
    {
        $this->studentEmail = $studentEmail;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;
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

    public function getPreferredDate(): ?\DateTimeInterface
    {
        return $this->preferredDate;
    }

    public function setPreferredDate(\DateTimeInterface $preferredDate): self
    {
        $this->preferredDate = $preferredDate;
        return $this;
    }

    public function getPreferredTimeSlot(): ?string
    {
        return $this->preferredTimeSlot;
    }

    public function setPreferredTimeSlot(string $preferredTimeSlot): self
    {
        $this->preferredTimeSlot = $preferredTimeSlot;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}