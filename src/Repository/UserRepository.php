<?php

namespace App\Repository;

use App\Entity\User; // Importation de l'entité User
use App\Entity\Matiere; // Importation de l'entité Matiere
use App\Entity\Niveau; // Importation de l'entité Niveau
use Doctrine\Persistence\ManagerRegistry; // Importation de ManagerRegistry pour la gestion de l'entité
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface; // Interface pour la mise à jour des mots de passe
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour les repositories d'entités
use Symfony\Component\Security\Core\Exception\UnsupportedUserException; // Exception pour utilisateur non supporté
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface; // Interface pour utilisateur authentifié par mot de passe

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface // Définition de la classe UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class); // Appel du constructeur parent avec le registre et l'entité User
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * Cette méthode permet de mettre à jour le mot de passe de l'utilisateur.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) { // Vérification si l'utilisateur est bien une instance de User
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class)); // Lève une exception si ce n'est pas le cas
        }

        $user->setPassword($newHashedPassword); // Met à jour le mot de passe de l'utilisateur
        $this->getEntityManager()->persist($user); // Persiste l'utilisateur avec le nouveau mot de passe
        $this->getEntityManager()->flush(); // Enregistre les changements dans la base de données
    }

    /**
     * Find tutors by subject.
     * Cette méthode permet de trouver les tuteurs associés à une matière donnée.
     */
    public function findTuteursByMatiere(Matiere $matiere): array
    {
        return $this->createQueryBuilder('u') // Création d'un query builder pour l'entité User
            ->join('u.matieres', 'm') // Jointure avec l'entité Matiere
            ->where('m = :matiere') // Condition pour filtrer par matière
            ->andWhere('u.roles LIKE :role') // Condition pour filtrer par rôle
            ->setParameter('matiere', $matiere) // Définition du paramètre pour la matière
            ->setParameter('role', '%"ROLE_TUTEUR"%') // Définition du paramètre pour le rôle de tuteur
            ->getQuery() // Obtention de la requête
            ->getResult(); // Exécution de la requête et obtention des résultats
    }

    /**
     * Find tutors by filters including subject and level.
     * Cette méthode permet de trouver les tuteurs en fonction de la matière et du niveau.
     */
    public function findTutorsByFilters(?Matiere $matiere = null, ?Niveau $niveau = null)
    {
        $qb = $this->createQueryBuilder('u') // Création d'un query builder pour l'entité User
            ->andWhere('u.roles LIKE :role') // Condition pour filtrer par rôle de tuteur
            ->setParameter('role', '%"ROLE_TUTEUR"%'); // Définition du paramètre pour le rôle de tuteur

        if ($matiere) { // Vérification si une matière est fournie
            $qb->leftJoin('u.matieres', 'm') // Jointure avec l'entité Matiere
                ->andWhere('m = :matiere') // Condition pour filtrer par matière
                ->setParameter('matiere', $matiere); // Définition du paramètre pour la matière
        }

        if ($niveau) { // Vérification si un niveau est fourni
            $qb->leftJoin('u.niveaux', 'n') // Jointure avec l'entité Niveau
                ->andWhere('n = :niveau') // Condition pour filtrer par niveau
                ->setParameter('niveau', $niveau); // Définition du paramètre pour le niveau
        }

        return $qb->getQuery()->getResult(); // Exécution de la requête et obtention des résultats
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
        ->andWhere('u.roles LIKE :role')
        ->setParameter('role', '%"' . $role . '"%')
        ->getQuery()
        ->getResult();
    }

    public function findTutors()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_TUTEUR"%')
            ->orderBy('u.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAvailableTutors(\DateTime $startTime, \DateTime $endTime)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('App\Entity\Reservation', 'r', 'WITH', 'r.tutor = u')
            ->where('u.roles LIKE :role')
            ->andWhere('
                r.id IS NULL OR 
                (r.startTime > :endTime OR r.endTime < :startTime)
            ')
            ->setParameter('role', '%"ROLE_TUTEUR"%')
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime)
            ->groupBy('u.id')
            ->orderBy('u.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return User[] Returns an array of User objects
    //     * Exemple de méthode pour trouver des utilisateurs par un champ spécifique.
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val') // Condition pour filtrer par exempleField
    //            ->setParameter('val', $value) // Définition du paramètre pour exampleField
    //            ->orderBy('u.id', 'ASC') // Tri des résultats par id
    //            ->setMaxResults(10) // Limitation du nombre de résultats à 10
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    /**
    //     * @return User|null Returns a User object or null
    //     * Exemple de méthode pour trouver un utilisateur par un champ spécifique.
    //     */
    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val') // Condition pour filtrer par exampleField
    //            ->setParameter('val', $value) // Définition du paramètre pour exampleField
    //            ->getQuery()
    //            ->getOneOrNullResult() // Obtention d'un seul résultat ou null
    //        ;
    //    }
}
