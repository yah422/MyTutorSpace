<?php
namespace App\Repository;

use App\Entity\Progress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Progress>
 *
 * @method Progress|null find($id, $lockMode = null, $lockVersion = null)
 * @method Progress|null findOneBy(array $criteria, array $orderBy = null)
 * @method Progress[]    findAll()
 * @method Progress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Progress::class);
    }

    /**
     * Find all progress records for a given set of dependents.
     *
     * @param array $dependents
     * @return Progress[]
     */
    public function findByDependents(array $dependents): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.dependent IN (:dependents)')
            ->setParameter('dependents', $dependents)
            ->getQuery()
            ->getResult();
    }
}
