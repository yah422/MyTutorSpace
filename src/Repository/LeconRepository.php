<?php

namespace App\Repository;

use App\Entity\Lecon;
use App\Entity\Niveau;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Lecon>
 */
class LeconRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lecon::class);
    }

    public function findLeconsParNiveau()
    {
        return $this->createQueryBuilder('l')
            ->join('l.niveau', 'n')
            ->addSelect('n')
            ->orderBy('n.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLeconsByNiveau(Niveau $niveau)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.niveaux', 'n')
            ->where('n.id = :niveauId')
            ->setParameter('niveauId', $niveau->getId())
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Lecon[] Returns an array of Lecon objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lecon
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
