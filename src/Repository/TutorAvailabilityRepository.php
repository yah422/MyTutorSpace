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

    public function findAvailabilitiesForPeriod($tuteur, \DateTime $startDate, \DateTime $endDate): array
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.tuteur = :tuteur')
            ->andWhere('a.startTime >= :startDate')
            ->andWhere('a.endTime <= :endDate')
            ->setParameter('tuteur', $tuteur)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        return $qb->getQuery()->getResult();
    }

    public function findAvailabilitiesForTutor(int $tutorId, \DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.tutor = :tutor')
            ->andWhere('ta.startTime >= :startDate')
            ->andWhere('ta.endTime <= :endDate')
            ->setParameter('tutor', $tutorId)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('ta.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
