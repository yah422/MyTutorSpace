<?php

namespace App\Controller;

use App\Services\TutorAvailabilityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tutor/availability')]
class TutorAvailabilityController extends AbstractController
{
    private $availabilityManager;

    public function __construct(TutorAvailabilityManager $availabilityManager)
    {
        $this->availabilityManager = $availabilityManager;
    }

    #[Route('/new', name: 'app_tutor_availability_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['start'], $data['end'])) {
            return $this->json(['error' => 'Missing required fields: start, end'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $availability = $this->availabilityManager->createAvailability(
                $this->getUser(),
                new \DateTime($data['start']),
                new \DateTime($data['end']),
                $data['isRecurring'] ?? false,
                $data['recurrencePattern'] ?? null
            );

            return $this->json([
                'id' => $availability->getId(),
                'start' => $availability->getStart()->format('Y-m-d\TH:i:s'),
                'end' => $availability->getEnd()->format('Y-m-d\TH:i:s'),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/edit', name: 'app_tutor_availability_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['start'], $data['end'])) {
            return $this->json(['error' => 'Missing required fields: start, end'], Response::HTTP_BAD_REQUEST);
        }

        $availability = $this->availabilityManager->findAvailability($id);

        if (!$availability) {
            return $this->json(['error' => 'Availability not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $availability = $this->availabilityManager->updateAvailability(
                $availability,
                new \DateTime($data['start']),
                new \DateTime($data['end']),
                $data['isRecurring'] ?? false,
                $data['recurrencePattern'] ?? null
            );

            return $this->json([
                'id' => $availability->getId(),
                'start' => $availability->getStart()->format('Y-m-d\TH:i:s'),
                'end' => $availability->getEnd()->format('Y-m-d\TH:i:s'),
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
