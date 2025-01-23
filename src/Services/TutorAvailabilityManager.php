<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\TutorAvailability;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TutorAvailabilityRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TutorAvailabilityManager
{
    private $entityManager;
    private $availabilityRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TutorAvailabilityRepository $availabilityRepository
    ) {
        $this->entityManager = $entityManager;
        $this->availabilityRepository = $availabilityRepository;
    }

    public function findAvailability(int $id): ?TutorAvailability
    {
        return $this->availabilityRepository->find($id);
    }

    public function createAvailability(
        User $tutor,
        \DateTime $start,
        \DateTime $end,
        bool $isRecurring = false,
        ?string $recurrencePattern = null
    ): TutorAvailability {
        // Validate time slot
        if ($start >= $end) {
            throw new BadRequestException('Start time must be before end time');
        }

        $availability = new TutorAvailability();
        $availability->setTutor($tutor)
            ->setStart($start)
            ->setEnd($end)
            ->setIsRecurring($isRecurring)
            ->setRecurrencePattern($recurrencePattern);

        // Check for overlapping availabilities
        $overlapping = $this->availabilityRepository->findOverlappingAvailabilities($availability);
        if (!empty($overlapping)) {
            throw new BadRequestException('This time slot overlaps with existing availabilities');
        }

        $this->entityManager->persist($availability);
        $this->entityManager->flush();

        return $availability;
    }

    public function updateAvailability(
        TutorAvailability $availability,
        \DateTime $start,
        \DateTime $end,
        bool $isRecurring = false,
        ?string $recurrencePattern = null
    ): TutorAvailability {
        if ($availability->isBooked()) {
            throw new BadRequestException('Cannot modify a booked availability');
        }

        $availability->setStart($start)
            ->setEnd($end)
            ->setIsRecurring($isRecurring)
            ->setRecurrencePattern($recurrencePattern);

        // Check for overlapping availabilities
        $overlapping = $this->availabilityRepository->findOverlappingAvailabilities($availability);
        if (!empty($overlapping)) {
            throw new BadRequestException('This time slot overlaps with existing availabilities');
        }

        $this->entityManager->flush();

        return $availability;
    }

    public function removeAvailability(TutorAvailability $availability): void
    {
        if ($availability->isBooked()) {
            throw new BadRequestException('Cannot remove a booked availability');
        }

        $this->entityManager->remove($availability);
        $this->entityManager->flush();
    }

    public function getAvailabilitiesForTutor(User $tutor, \DateTime $start, \DateTime $end): array
    {
        return $this->availabilityRepository->findAvailabilitiesForTutor($tutor, $start, $end);
    }
}
