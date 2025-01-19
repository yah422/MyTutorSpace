<?php
namespace App\Entity;

use App\Repository\ProgressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgressRepository::class)]
class Progress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $dependent = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $tutoringBooking = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDependent(): ?User
    {
        return $this->dependent;
    }

    public function setDependent(?User $dependent): self
    {
        $this->dependent = $dependent;

        return $this;
    }

    public function getTutoringBooking(): ?string
    {
        return $this->tutoringBooking;
    }

    public function setTutoringBooking(string $tutoringBooking): self
    {
        $this->tutoringBooking = $tutoringBooking;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
}