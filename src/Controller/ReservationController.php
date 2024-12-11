<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'reservation_list', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'reservation_new', methods: ['GET', 'POST'])]
    public function reserver(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle réservation
        $reservation = new Reservation();
        $reservation->setDateReservation(new \DateTime()); // Date actuelle

        // Créer le formulaire basé sur le type de formulaire ReservationType
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer l'utilisateur actuellement connecté à la réservation
            $reservation->setUser($this->getUser());

            // Persister et sauvegarder la réservation
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Rediriger vers la page de succès
            return $this->redirectToRoute('reservation_success');
        }

        // Afficher le formulaire s'il n'est pas soumis ou valide
        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/success', name: 'reservation_success', methods: ['GET'])]
    public function reservationSuccess(): Response
    {
        return $this->render('reservation/success.html.twig', [
            'message' => 'Votre réservation a été enregistrée avec succès !'
        ]);
    }

    #[Route('/{id}/cancel', name: 'reservation_cancel', methods: ['POST'])]
    public function cancel(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if (!$reservation->isCancelled()) {
            $reservation->cancel();
            $entityManager->flush();
            $this->addFlash('success', 'Réservation annulée.');
        } else {
            $this->addFlash('error', 'Réservation déjà annulée.');
        }

        return $this->redirectToRoute('reservation_list');
    }
}
