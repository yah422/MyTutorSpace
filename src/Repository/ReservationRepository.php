<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Lecon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findConflictingReservations(Lecon $lecon, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.lecon = :lecon')
            ->andWhere('r.statut = :statut')
            ->andWhere(
                '(r.dateDebut BETWEEN :debut AND :fin) OR
                (r.dateFin BETWEEN :debut AND :fin) OR
                (:debut BETWEEN r.dateDebut AND r.dateFin)'
            )
            ->setParameters([
                'lecon' => $lecon,
                'statut' => 'confirmee',
                'debut' => $dateDebut,
                'fin' => $dateFin
            ])
            ->getQuery()
            ->getResult();
    }

    public function findByEleve(User $eleve): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.eleve = :eleve')
            ->setParameter('eleve', $eleve)
            ->orderBy('r.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByTuteur(User $tuteur): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.lecon', 'l')
            ->where('l.user = :tuteur')
            ->setParameter('tuteur', $tuteur)
            ->orderBy('r.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }
}