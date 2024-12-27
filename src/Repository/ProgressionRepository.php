<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Progression;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Progression>
 */
class ProgressionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Progression::class);
    }
    public function findByEleve(User $eleve)
    {
        return $this->createQueryBuilder('p')
            ->select('p')  // Select the entire entity
            ->where('p.eleve = :eleve')
            ->setParameter('eleve', $eleve)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Progression[] Returns an array of Progression objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    
    //    public function findOneBySomeField($value): ?Progression
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
