<?php

namespace App\Entity;

use App\Repository\TutorAvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TutorAvailabilityRepository::class)]
class TutorAvailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $tutor = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(propertyPath: "start")]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isBooked = false;

    #[ORM\OneToOne(targetEntity: TutoringBooking::class, mappedBy: 'availability', cascade: ['persist'])]
    private ?TutoringBooking $booking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTutor(): ?User
    {
        return $this->tutor;
    }

    public function setTutor(?User $tutor): self
    {
        $this->tutor = $tutor;
        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;
        return $this;
    }

    public function isBooked(): bool
    {
        return $this->isBooked;
    }

    public function setIsBooked(bool $isBooked): self
    {
        $this->isBooked = $isBooked;
        return $this;
    }

    public function getBooking(): ?TutoringBooking
    {
        return $this->booking;
    }

    public function setBooking(?TutoringBooking $booking): self
    {
        $this->booking = $booking;
        return $this;
    }

    public function getDuration(): \DateInterval
    {
        return $this->start->diff($this->end);
    }

    public function overlaps(TutorAvailability $other): bool
    {
        return $this->start < $other->end && $this->end > $other->start;
    }
}
