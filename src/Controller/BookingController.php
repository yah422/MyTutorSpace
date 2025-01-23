<?php

namespace App\Controller;

use App\Entity\TutoringBooking;
use App\Services\BookingMailer;
use App\Entity\TutorAvailability;
use App\Form\TutoringBookingType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TutoringBookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    #[Route('/booking/calendar', name: 'app_booking_calendar')]
    public function calendar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booking = new TutoringBooking();
        $form = $this->createForm(TutoringBookingType::class, $booking);

        return $this->render('booking/calendar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking/create', name: 'app_booking')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        BookingMailer $bookingMailer
    ): Response {
        $booking = new TutoringBooking();
        $form = $this->createForm(TutoringBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();

            // Send confirmation emails
            $bookingMailer->sendConfirmationEmail($booking);

            $this->addFlash('success', 'Votre réservation a été confirmée. Un email de confirmation vous a été envoyé.');

            return $this->redirectToRoute('app_booking_confirmation', ['id' => $booking->getId()]);
        }

        return $this->render('booking/calendar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/bookings', name: 'booking_list')]
    #[IsGranted('ROLE_USER')]
    public function listBookings(TutoringBookingRepository $bookingRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_TUTEUR')) {
            // Get bookings that need confirmation from the tutor
            $bookings = $bookingRepository->findBy(['tuteur' => $user, 'status' => 'pending']);
        } elseif ($this->isGranted('ROLE_PARENT')) {
            // Get bookings for the children of the parent
            $bookings = $bookingRepository->findByParentChildren($user);
        } else {
            // For students, show their own bookings
            $bookings = $bookingRepository->findBy(['eleve' => $user]);
        }

        return $this->render('booking/list.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/booking/{id}/confirm', name: 'booking_confirm')]
    public function confirmBooking(TutoringBooking $booking, EntityManagerInterface $entityManager): Response
    {
        $booking->setStatus('confirmed');
        $entityManager->flush();

        $this->addFlash('success', 'Réservation confirmée.');
        return $this->redirectToRoute('booking_list');
    }

    #[Route('/booking/{id}/cancel', name: 'booking_cancel')]
    public function cancelBooking(TutoringBooking $booking, EntityManagerInterface $entityManager): Response
    {
        $booking->setStatus('canceled');
        $entityManager->flush();

        $this->addFlash('error', 'Réservation annulée.');
        return $this->redirectToRoute('booking_list');
    }


    #[Route('/booking/confirmation/{id}', name: 'app_booking_confirmation')]
    public function confirmation(TutoringBooking $booking): Response
    {
        return $this->render('booking/confirmation.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/tutor-availabilities', name: 'app_tutor_availabilities')]
    public function getTutorAvailabilities(EntityManagerInterface $entityManager): JsonResponse
    {
        $availabilities = $entityManager->getRepository(TutorAvailability::class)->findAll();

        $events = [];
        foreach ($availabilities as $availability) {
            $events[] = [
                'id' => $availability->getId(),
                'title' => 'Disponible',
                'start' => $availability->getStart()->format('Y-m-d\TH:i:s'),
                'end' => $availability->getEnd()->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $availability->isBooked() ? '#FF6347' : '#90EE90',
                'borderColor' => $availability->isBooked() ? '#FF6347' : '#90EE90',
            ];
        }

        return new JsonResponse($events);
    }
}
