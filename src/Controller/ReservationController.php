<?php

namespace App\Controller;

use App\Entity\Lecon;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index')]
    // #[IsGranted('ROLE_USER')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        
        if ($this->isGranted('ROLE_TUTEUR')) {
            $reservations = $reservationRepository->findByTuteur($user);
        } else {
            $reservations = $reservationRepository->findByEleve($user);
        }

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_new')]
    // #[IsGranted('ROLE_USER')]
    public function new(Request $request, Lecon $lecon, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_TUTEUR')) {
            $reservations = $reservationRepository->findByTuteur($user);
        } else {
            $reservations = $reservationRepository->findByEleve($user);
        }

        $reservation = new Reservation();
        $reservation->setLecon($lecon);
        $reservation->setEleve($this->getUser());

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On vérifie les conflits de réservation
            $conflits = $reservationRepository->findConflictingReservations(
                $lecon,
                $reservation->getDateDebut(),
                $reservation->getDateFin()
            );

            if (count($conflits) > 0) {
                $this->addFlash('error', 'Cette plage horaire n\'est pas disponible.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $lecon->getId()]);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande de réservation a été envoyée avec succès.');
            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'reservations' => $reservations,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit')]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        // On vérifie que l'utilisateur est soit le tuteur soit l'élève
        if ($this->getUser() !== $reservation->getEleve() && 
            $this->getUser() !== $reservation->getLecon()->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/status/{newStatus}', name: 'app_reservation_status')]
    #[IsGranted('ROLE_TUTEUR')]
    public function updateStatus(Reservation $reservation, string $newStatus, EntityManagerInterface $entityManager): Response
    {
        // On vérifie que le tuteur est bien celui qui donne le cours
        if ($this->getUser() !== $reservation->getLecon()->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if (in_array($newStatus, ['confirmee', 'annulee'])) {
            $reservation->setStatut($newStatus);
            $entityManager->flush();
            
            $message = $newStatus === 'confirmee' ? 'confirmée' : 'annulée';
            $this->addFlash('success', "La réservation a été $message.");
        }

        return $this->redirectToRoute('app_reservation_index');
    }
}