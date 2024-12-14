<?php

namespace App\Services\Calendar;

use App\Entity\User;
use App\Entity\TutorAvailability;
use Doctrine\ORM\EntityManagerInterface;

class AvailabilityManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function createAvailability(User $tuteur, \DateTime $startTime, \DateTime $endTime): TutorAvailability
    {
        if (!$this->isTuteur($tuteur)) {
            throw new \InvalidArgumentException('User must have ROLE_TUTEUR to create availability');
        }

        $availability = new TutorAvailability();
        $availability->setTuteur($tuteur)
            ->setStartTime($startTime)
            ->setEndTime($endTime);

        $this->entityManager->persist($availability);
        $this->entityManager->flush();

        return $availability;
    }

    public function removeAvailability(TutorAvailability $availability): void
    {
        $this->entityManager->remove($availability);
        $this->entityManager->flush();
    }

    private function isTuteur(User $user): bool
    {
        return in_array('ROLE_TUTEUR', $user->getRoles());
    }
}