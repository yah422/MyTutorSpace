<?php

namespace App\Repository;

use App\Entity\Lecon;
use App\Entity\Niveau;
use App\Entity\Matiere;
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

    public function findLeconsByNiveau(Niveau $niveau)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.niveaux', 'n')
            ->where('n.id = :niveauId')
            ->setParameter('niveauId', $niveau->getId())
            ->getQuery()
            ->getResult();
    }

    public function findLeconsByFilters(?Matiere $matiere = null, ?Niveau $niveau = null)
    {
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.matiere', 'm')
            ->leftJoin('l.niveaux', 'n')
            ->orderBy('l.titre', 'ASC');

        if ($matiere) {
            $qb->andWhere('l.matiere = :matiere')
            ->setParameter('matiere', $matiere);
        }

        if ($niveau) {
            $qb->andWhere(':niveau MEMBER OF l.niveaux')
            ->setParameter('niveau', $niveau);
        }

        return $qb->getQuery()->getResult();
    }

    public function findLeconsByNiveauAndMatiere(Niveau $niveau, Matiere $matiere)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.niveaux', 'n')
            ->innerJoin('l.matiere', 'm')
            ->where('n.id = :niveauId')
            ->andWhere('m.id = :matiereId')
            ->setParameter('niveauId', $niveau->getId())
            ->setParameter('matiereId', $matiere->getId())
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
