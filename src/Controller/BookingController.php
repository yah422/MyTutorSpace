<?php

namespace App\Controller;

use App\Entity\TutoringBooking;
use App\Entity\TutorAvailability;
use App\Form\TutoringBookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TutorAvailabilityRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booking = new TutoringBooking();
        $booking->setTuteur($this->getUser());
        $form = $this->createForm(TutoringBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande de réservation a été enregistrée avec succès ! Nous vous contacterons rapidement pour confirmer votre créneau.');

            return $this->redirectToRoute('app_booking_confirmation', ['id' => $booking->getId()]);
        }

        return $this->render('booking/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking/confirmation/{id}', name: 'app_booking_confirmation')]
    public function confirmation(TutoringBooking $booking): Response
    {
        return $this->render('booking/confirmation.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function calendar(): Response
    {
        return $this->render('booking/calendar.html.twig');
    }

    #[Route("/tutor-availabilities", name: "app_tutor_availabilities")]
    public function getTutorAvailabilities(TutorAvailabilityRepository $repository): JsonResponse
    {
        $availabilities = $repository->findAll();

        $events = [];
        foreach ($availabilities as $availability) {
            $events[] = [
                'title' => 'Disponible',
                'start' => $availability->getStartTime()->format('Y-m-d\TH:i:s'),
                'end' => $availability->getEndTime()->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $availability->isBooked() ? '#FF6347' : '#90EE90',
                'borderColor' => $availability->isBooked() ? '#FF6347' : '#90EE90',
            ];
        }

        return new JsonResponse($events);
    }

    #[Route('/add-availability', name: 'app_add_tutor_availability', methods: ['POST'])]
    public function addTutorAvailability(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $availability = new TutorAvailability();
        $availability->setStartTime(new \DateTime($data['start']));
        $availability->setEndTime(new \DateTime($data['end']));

        $entityManager->persist($availability);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success'], JsonResponse::HTTP_CREATED);
    }
}