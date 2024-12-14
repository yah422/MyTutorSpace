<?php

namespace App\Services\Calendar;

use App\Entity\User;
use App\Repository\TutorAvailabilityRepository;

class AvailabilityFinder
{
    public function __construct(
        private TutorAvailabilityRepository $availabilityRepository
    ) {}

    public function findTutorAvailabilities(User $tuteur, \DateTime $startDate, \DateTime $endDate): array
    {
        if (!in_array('ROLE_TUTEUR', $tuteur->getRoles())) {
            throw new \InvalidArgumentException('User must have ROLE_TUTEUR to have availabilities');
        }

        return $this->availabilityRepository->findAvailabilitiesForPeriod($tuteur, $startDate, $endDate);
    }
}