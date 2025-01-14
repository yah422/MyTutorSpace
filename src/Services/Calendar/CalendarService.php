<?php

namespace App\Services\Calendar;

use App\Entity\User;
use App\Entity\TutorAvailability;
use App\Repository\TutorAvailabilityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Autowire]
class CalendarService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TutorAvailabilityRepository $availabilityRepository
    ) {
    }

    private function isTuteur(User $user): bool
    {
        $roles = $user->getRoles();

        // Ajout de debug pour vérifier les rôles.
        if (!in_array('ROLE_TUTEUR', $roles, true)) {
            throw new \LogicException(sprintf(
                'User %s does not have the required ROLE_TUTEUR. Current roles: %s',
                $user->getPrenom(),
                implode(', ', $roles)
            ));
        }

        return in_array('ROLE_TUTEUR', $roles, true);
    }


    public function getTutorAvailabilities(User $tuteur, \DateTime $startDate, \DateTime $endDate): array
    {
        if (!$this->isTuteur($tuteur)) {
            throw new \InvalidArgumentException('User must have ROLE_TUTEUR to have availabilities');
        }

        // Appel à la méthode définie dans le repository
        return $this->availabilityRepository->findAvailabilitiesForPeriod($tuteur, $startDate, $endDate);
    }


    public function addAvailability(User $tuteur, \DateTime $startTime, \DateTime $endTime): TutorAvailability
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

    // private function isTuteur(User $user): bool
    // {
    //     return in_array('ROLE_TUTEUR', $user->getRoles(), true);
    // }
}