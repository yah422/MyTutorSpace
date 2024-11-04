<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Matiere;
use App\Entity\Niveau;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findTuteursByMatiere(Matiere $matiere): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.matieres', 'm')
            ->where('m = :matiere')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('matiere', $matiere)
            ->setParameter('role', '%"ROLE_TUTEUR"%')
            ->getQuery()
            ->getResult();
    }

public function findTutorsByFilters(?Matiere $matiere = null, ?Niveau $niveau = null)
{
    $qb = $this->createQueryBuilder('u')
        ->andWhere('u.roles LIKE :role')
        ->setParameter('role', '%"ROLE_TUTEUR"%');

    if ($matiere) {
        $qb->leftJoin('u.matieres', 'm')
        ->andWhere('m = :matiere')
        ->setParameter('matiere', $matiere);
    }

    if ($niveau) {
        $qb->leftJoin('u.niveaux', 'n')
        ->andWhere('n = :niveau')
        ->setParameter('niveau', $niveau);
    }

    return $qb->getQuery()->getResult();
}



    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
