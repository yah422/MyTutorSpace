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
    public function __construct()
    {
        // Par défaut, on donne le rôle ROLE_USER à tous les nouveaux utilisateurs
        $this->roles = ['ROLE_USER'];
        $this->lecon = new ArrayCollection();
        $this->lecons = new ArrayCollection();
        $this->matieres = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: 'Veuillez entrer un email valide.')]  // Validation de l'email
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

    #[ORM\Column]
    private ?float $hourlyRate = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TutorAvailability::class, cascade: ['persist', 'remove'])]
    private Collection $availabilities;

    
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TutoringBooking::class, cascade: ['persist', 'remove'])]
    private ?Collection $tutoringBookings = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\ManyToMany(targetEntity: Niveau::class, inversedBy: "users")]
    private Collection $niveaux;

    #[ORM\Column(type: 'json')] // Définition du champ roles en tant que JSON
    private array $roles = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[ORM\Column(type: 'text')]
    private ?string $AboutMe = null;

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

    // Getter et setter pour hourlyRate
    public function getHourlyRate(): ?float
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(?float $hourlyRate): self
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

    // Getter et setter pour availabilities
    /**
     * @return Collection<int, TutorAvailability>
     */
    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function addAvailability(TutorAvailability $availability): self
    {
        if (!$this->availabilities->contains($availability)) {
            $this->availabilities->add($availability);
            $availability->setTuteur($this);
        }

        return $this;
    }

    public function removeAvailability(TutorAvailability $availability): self
    {
        if ($this->availabilities->removeElement($availability)) {
            // set the owning side to null (unless already changed)
            if ($availability->getTuteur() === $this) {
                $availability->setTuteur(null);
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




    /**
     * @Assert\NotBlank(message="Le mot de passe est obligatoire.")
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: Niveau::class)]
    private ?Niveau $niveau = null;

    #[UploadableField(mapping: 'user_photos', fileNameProperty: 'photo')]
    private ?File $photoFile = null;

    private ?string $photo = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    // Getters et Setters
    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;

        if ($photoFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
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
}
