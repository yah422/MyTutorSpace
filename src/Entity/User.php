<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var string[] The user roles
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\Choice(
     *     choices={"tuteur", "parent", "eleve"},
     *     message="Le rôle doit être soit 'tuteur', 'parent' ou 'eleve'."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $role = null; // Un seul rôle par utilisateur

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
     * Cette méthode retourne l'identifiant utilisateur (email dans ce cas)
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Cette méthode renvoie les rôles de l'utilisateur.
     * Le rôle est basé sur le champ `role` et est converti en format Symfony (ROLE_TUTEUR, ROLE_ELEVE, etc.)
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Ajout du rôle principal basé sur le champ role
        if ($this->role) {
            $roles[] = 'ROLE_' . strtoupper($this->role);
        }

        // ROLE_USER est garanti pour tous les utilisateurs
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Le setter des rôles ne sert pas dans ce cas, puisque le rôle est défini via le champ `role`.
     * Cette méthode peut être laissée vide ou redéfinie selon tes besoins futurs.
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Effacement des données sensibles (e.g., plain password)
     */
    public function eraseCredentials(): void
    {
        // Si tu stockes des données sensibles temporairement, elles peuvent être effacées ici.
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Setter pour le rôle, avec validation du rôle choisi (tuteur, parent, élève)
     */
    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
