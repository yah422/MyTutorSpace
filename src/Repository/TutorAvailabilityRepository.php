<?php

namespace App\Repository;

use App\Entity\TutorAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TutorAvailability>
 *
 * @method TutorAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method TutorAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method TutorAvailability[]    findAll()
 * @method TutorAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutorAvailability::class);
    }

}
