<?php

namespace App\Entity;


use Twilio\Http\File;
use App\Entity\Reservation;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: 'Veuillez entrer un email valide.')]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Matiere::class, inversedBy: "users")]
    private Collection $matieres;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'user')]
    private Collection $contacts;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire.')]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(type: "decimal", scale: 2, nullable: true)]
    private $hourlyRate;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'sender')]
    private Collection $sent;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'receiver')]
    private Collection $received;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = 'default-profile.png'; // Image par défaut

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TutoringBooking::class, cascade: ['persist', 'remove'])]
    private ?Collection $tutoringBookings = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\ManyToMany(targetEntity: Niveau::class, inversedBy: "users")]
    private Collection $niveaux;

    #[ORM\Column(type: 'json')] // Définition du champ roles en tant que JSON
    private array $roles = [];

    /**
     * @Assert\NotBlank(message="Le mot de passe est obligatoire.")
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: Niveau::class)]
    private ?Niveau $niveau = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;
    /**
     * @var Collection<int, Lecon>
     */
    #[ORM\OneToMany(targetEntity: Lecon::class, mappedBy: 'user')]
    private Collection $lecon;

    /**
     * @var Collection<int, Lecon>
     */
    #[ORM\ManyToMany(targetEntity: Lecon::class, inversedBy: 'users')]
    private Collection $lecons;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[ORM\Column(type: 'text')]
    private ?string $AboutMe = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $dependents;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'dependents')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?self $parent = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $tuteur = null;

    #[ORM\Column(type: "boolean")]
    private bool $banned = false;

    public function __construct()
    {
        // Par défaut, on donne le rôle ROLE_USER à tous les nouveaux utilisateurs
        $this->roles = ['ROLE_USER'];
        $this->dependents = new ArrayCollection();
        $this->lecon = new ArrayCollection();
        $this->lecons = new ArrayCollection();
        $this->matieres = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
        $this->eleves = new ArrayCollection();
        $this->sauvegardeProfils = new ArrayCollection();
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
    }

    // Getter et setter pour phone
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(Message $sent): static
    {
        if (!$this->sent->contains($sent)) {
            $this->sent->add($sent);
            $sent->setSender($this);
        }

        return $this;
    }

    public function removeSent(Message $sent): static
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getSender() === $this) {
                $sent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(Message $received): static
    {
        if (!$this->received->contains($received)) {
            $this->received->add($received);
            $received->setReceiver($this);
        }

        return $this;
    }

    public function removeReceived(Message $received): static
    {
        if ($this->received->removeElement($received)) {
            // set the owning side to null (unless already changed)
            if ($received->getReceiver() === $this) {
                $received->setReceiver(null);
            }
        }

        return $this;
    }

    public function getTutoringBookings(): ?Collection
    {
        return $this->tutoringBookings;
    }

    public function setTutoringBookings(?Collection $tutoringBookings): self
    {
        $this->tutoringBookings = $tutoringBookings;

        return $this;
    }

    public function addTutoringBooking(TutoringBooking $tutoringBooking): self
    {
        if (!$this->tutoringBookings->contains($tutoringBooking)) {
            $this->tutoringBookings[] = $tutoringBooking;
            $tutoringBooking->setTuteur($this);
        }

        return $this;
    }

    public function removeTutoringBooking(TutoringBooking $tutoringBooking): self
    {
        if ($this->tutoringBookings->contains($tutoringBooking)) {
            $this->tutoringBookings->removeElement($tutoringBooking);
            // set the owning side to null (unless already changed)
            if ($tutoringBooking->getTuteur() === $this) {
                $tutoringBooking->setTuteur(null);
            }
        }

        return $this;
    }


    // Getter et setter pour updatedAt
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        $this->niveaux->removeElement($niveau);

        return $this;
    }

    /**
     * Retourne les rôles de l'utilisateur, en ajoutant ROLE_USER s'il n'est pas déjà présent.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';  // Chaque utilisateur doit au moins avoir ce rôle

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
        }
        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        $this->matieres->removeElement($matiere);
        return $this;
    }

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $plainPassword = null;

    /**
     * @var Collection<int, SauvegardeProfil>
     */
    #[ORM\OneToMany(targetEntity: SauvegardeProfil::class, mappedBy: 'user')]
    private Collection $sauvegardeProfils;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * Cette méthode retourne l'identifiant utilisateur (email dans ce cas).
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Efface les informations sensibles après leur traitement (plain password).
     */
    public function eraseCredentials(): void
    {
        // Supprimer les données sensibles temporairement stockées
        $this->plainPassword = null;
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

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    /**
     * @return Collection<int, Lecon>
     */
    public function getLecon(): Collection
    {
        return $this->lecon;
    }

    public function addLecon(Lecon $lecon): self
    {
        if (!$this->lecons->contains($lecon)) {
            $this->lecons->add($lecon);
            $lecon->addUser($this);
        }

        return $this;
    }

    public function removeLecon(Lecon $lecon): self
    {
        if ($this->lecons->removeElement($lecon)) {
            if ($lecon->getUsers()->contains($this)) {
                $lecon->removeUser($this);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lecon>
     */
    public function getLecons(): Collection
    {
        return $this->lecons;
    }

    public function getAboutMe(): ?string
    {
        return $this->AboutMe;
    }

    public function setAboutMe(string $AboutMe): static
    {
        $this->AboutMe = $AboutMe;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDependents(): Collection
    {
        return $this->dependents;
    }

    public function addChild(self $child): self
    {
        if (!$this->dependents->contains($child)) {
            $this->dependents->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->dependents->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getParent(): ?User
    {
        return $this->parent;
    }

    public function setParent(?User $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    // Helper methods for parent/child relationship
    public function isChild(): bool
    {
        return $this->parent !== null;
    }

    public function isParent(): bool
    {
        return !$this->dependents->isEmpty();
    }

    public function hasParent(): bool
    {
        return $this->parent !== null;
    }

    public function hasDependents(): bool
    {
        return !$this->dependents->isEmpty();
    }

    /**
     * @return Collection<int, SauvegardeProfil>
     */
    public function getSauvegardeProfils(): Collection
    {
        return $this->sauvegardeProfils;
    }

    public function addSauvegardeProfil(SauvegardeProfil $sauvegardeProfil): static
    {
        if (!$this->sauvegardeProfils->contains($sauvegardeProfil)) {
            $this->sauvegardeProfils->add($sauvegardeProfil);
            $sauvegardeProfil->setUser($this);
        }

        return $this;
    }

    public function removeSauvegardeProfil(SauvegardeProfil $sauvegardeProfil): static
    {
        if ($this->sauvegardeProfils->removeElement($sauvegardeProfil)) {
            // set the owning side to null (unless already changed)
            if ($sauvegardeProfil->getUser() === $this) {
                $sauvegardeProfil->setUser(null);
            }
        }

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

    public function getHourlyRate(): ?int
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(?int $hourlyRate): self
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

    public function isBanned(): bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

}
