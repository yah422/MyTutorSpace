<?php

namespace App\Repository;

use App\Entity\TutorAvailability;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TutorAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutorAvailability::class);
    }

    /**
     * Trouve les disponibilités d'un tuteur sur une période donnée.
     *
     * @param User $tuteur
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return TutorAvailability[]
     */
    public function findAvailabilitiesForPeriod(User $tuteur, \DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.tuteur = :tuteur')
            ->andWhere('a.startTime >= :startDate')
            ->andWhere('a.endTime <= :endDate')
            ->setParameter('tuteur', $tuteur)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('a.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve une disponibilité par son ID et par tuteur.
     *
     * @param int $id
     * @param User $tuteur
     * @return TutorAvailability|null
     */
    public function findByIdAndTutor(int $id, User $tuteur): ?TutorAvailability
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :id')
            ->andWhere('a.tuteur = :tuteur')
            ->setParameter('id', $id)
            ->setParameter('tuteur', $tuteur)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
