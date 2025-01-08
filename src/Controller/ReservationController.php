<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Lecon;
use App\Repository\UserRepository;
use App\Repository\LeconRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(UserRepository $userRepository, LeconRepository $leconRepository): Response
    {
        $tutors = $userRepository->findByRole('ROLE_TUTEUR');
        $lecons = $leconRepository->findAll();

        return $this->render('reservation/index.html.twig', [
            'tutors' => $tutors,
            'lecons' => $lecons,
        ]);
    }

    #[Route('/reservation/slots', name: 'app_reservation_slots')]
    public function getAvailableSlots(Request $request, UserRepository $userRepository): Response
    {
        $tutorId = $request->query->get('tutor');
        $date = $request->query->get('date');
        
        $tutor = $userRepository->find($tutorId);
        // Logic to get available slots based on tutor's availability
        $slots = $this->generateTimeSlots($tutor, new \DateTime($date));

        return $this->json($slots);
    }

    #[Route('/reservation/create', name: 'app_reservation_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $reservation = new Reservation();
        // Set reservation properties based on request data
        
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->json(['status' => 'success']);
    }

    private function generateTimeSlots(User $tutor, \DateTime $date): array
    {
        // Implementation of time slot generation logic
        // This should check tutor's availability and existing reservations
        return [];
    }
}
