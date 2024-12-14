<?php

namespace App\Controller;

use App\Entity\TutoringBooking;
use App\Form\TutoringBookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
}