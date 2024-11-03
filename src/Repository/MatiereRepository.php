<?php

namespace App\Repository;

use App\Entity\Matiere;
use App\Entity\Niveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matiere>
 */
class MatiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matiere::class);
    }

    public function findMatieresWithNiveaux()
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.lecons', 'l')
            ->leftJoin('l.niveaux', 'n')
            ->select('m', 'n')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findMatieresByNiveau(Niveau $niveau)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.lecons', 'l')
            ->innerJoin('l.niveaux', 'n')
            ->where('n.id = :niveauId')
            ->setParameter('niveauId', $niveau->getId())
            ->orderBy('m.nom', 'ASC')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
}
