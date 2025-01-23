<?php

namespace App\Repository;

use App\Entity\TutoringBooking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TutoringBooking>
 *
 * @method TutoringBooking|null find($id, $lockMode = null, $lockVersion = null)
 * @method TutoringBooking|null findOneBy(array $criteria, array $orderBy = null)
 * @method TutoringBooking[]    findAll()
 * @method TutoringBooking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutoringBookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutoringBooking::class);
    }

    /**
     * Trouve les réservations pour une période donnée
     */
    public function findBookingsForPeriod(\DateTime $start, \DateTime $end): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.preferredDate >= :start')
            ->andWhere('b.preferredDate <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('b.preferredDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réservations en attente
     */
    public function findPendingBookings(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.status = :status')
            ->setParameter('status', 'pending')
            ->orderBy('b.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réservations pour un tuteur spécifique
     */
    public function findBookingsForTutor(int $tuteurId): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.tuteur = :tutorId')
            ->setParameter('tuteurId', $tuteurId)
            ->orderBy('b.preferredDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

        /**
     * Get bookings for a parent's children.
     */
    public function findByParentChildren($parent)
    {
        return $this->createQueryBuilder('b')
            ->join('b.eleve', 'e')
            ->join('e.parents', 'p') // Ensure your `eleve` entity has a relationship with parents
            ->where('p.id = :parentId')
            ->setParameter('parentId', $parent->getId())
            ->getQuery()
            ->getResult();
    }
}