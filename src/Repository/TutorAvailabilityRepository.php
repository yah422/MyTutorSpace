<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\TutorAvailability;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TutorAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutorAvailability::class);
    }

    public function findAvailabilitiesForTutor(User $tutor, \DateTime $start, \DateTime $end)
    {
        return $this->createQueryBuilder('a')
            ->where('a.tutor = :tutor')
            ->andWhere('a.start >= :start')
            ->andWhere('a.end <= :end')
            ->andWhere('a.isBooked = :isBooked')
            ->setParameter('tutor', $tutor)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('isBooked', false)
            ->orderBy('a.start', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOverlappingAvailabilities(TutorAvailability $availability)
    {
        return $this->createQueryBuilder('a')
            ->where('a.tutor = :tutor')
            ->andWhere('a.id != :id')
            ->andWhere('
                (a.start < :end AND a.end > :start)
            ')
            ->setParameter('tutor', $availability->getTutor())
            ->setParameter('id', $availability->getId() ?? 0)
            ->setParameter('start', $availability->getStart())
            ->setParameter('end', $availability->getEnd())
            ->getQuery()
            ->getResult();
    }
}
