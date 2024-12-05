<?php

namespace App\Controller;

use App\Entity\Lecon;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Services\EmailService;
use App\Services\SmsGenerator;
use App\Repository\LeconRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    private $htmlSanitizer;
    private $csrfTokenManager;
    private $emailService;

    public function __construct(HtmlSanitizerInterface  $htmlSanitizer, CsrfTokenManagerInterface $csrfTokenManager, EmailService $emailService)
    {
        $this->htmlSanitizer = $htmlSanitizer;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->emailService = $emailService;
    }

    //gestioncréation d'une réservation
    #[Route('/reservation', name: 'app_reservation')]
    public function addReservation(
        Request $request,
        Security $security,
        EntityManagerInterface $entityManager,
        MatiereRepository $matiereRepository,
        LeconRepository $leconRepository,
        MailerInterface $mailer,
        EmailService $emailService,
        SmsGenerator $smsGenerator,
    ): Response {
        $lecons = $leconRepository->findAll();
        $matieres = $matiereRepository->findAll();

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // HoneyPot 
            $honeypotValue = $form->get('prenom')->getData();

            if (!empty($honeypotValue)) {
                // Le champ a été rempli, probablement un bot
                return $this->redirectToRoute('app_home');
            }

            // Récupère les données du formulaire
            $reservation = $form->getData();

            // Sanitize les champs du formulaire
            $reservation->setNom($this->htmlSanitizer->sanitize($reservation->getNom()));
            $reservation->setPrenom($this->htmlSanitizer->sanitize($reservation->getPrenom()));
            $reservation->setMessage($this->htmlSanitizer->sanitize($reservation->getMessage()));
            // Vérifie si l'adresse email est valide
            $emailAddress = $reservation->getEmail();
            if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                $this->addFlash('error', 'Adresse email invalide.');
                return $this->redirectToRoute('app_reservation');
            }
            // Récupère le créneau horaire sélectionné depuis la requête
            $selectedSlot = $request->request->get('selectedSlot');

            // si un créneau horaire a été sélectionné
            if ($selectedSlot) {
                // Crée des objets DateTime pour le début et la fin de la réservation
                $dateDebut = new \DateTime($selectedSlot);
                $dateFin = clone $dateDebut; // Clone la date de début
                $dateFin->modify('+1 hour'); // Ajoute une heure à la date de fin

                // Définit les dates de début et de fin de la réservation
                $reservation->setDateDebut($dateDebut);
                $reservation->setDateFin($dateFin);

                //Vérifie si un utilisateur est connecté
                $user = $security->getUser();

                // Si un utilisateur est connecté, associe ses informations à la réservation
                if ($user) {
                    $reservation->setUser($user);
                }

                // Persiste la réservation dans la base de données
                $entityManager->persist($reservation);
                $entityManager->flush();

                $emailService->sendConfirmationEmailTo($mailer, $emailAddress, $dateDebut);
                $emailService->sendConfirmationEmailFrom($mailer, $emailAddress, $dateDebut);
                $message = 'Nouveau message de ' . $reservation->getNom() . ' ' . $reservation->getPrenom() . ' : ' . $reservation->getMessage();
                $smsGenerator->sendSms($message); // Envoie un SMS à MyTutorSpace

                // Ajoute un message de succès et redirige vers la page d'accueil
                $this->addFlash('success', 'Votre réservation a été enregistrée avec succès. Un email de confirmation vous a été envoyé.');
                return $this->redirectToRoute('app_home');
            } else {
                // Si aucun créneau horaire n'est sélectionné, on ajoute un message d'erreur
                $this->addFlash('error', 'Veuillez sélectionner un créneau horaire.');
            }
        }

        return $this->render('reservation/reservation.html.twig', [
            'form' => $form->createView(),
            'title' => 'Prise de réservation',
            'lecons' => $lecons,
            'matieres' => $matieres,
        ]);
    }

    // Récupère les créneaux horaires disponibles pour une date donnée
    #[Route('/available_rdv', name: 'available_rdv', methods: ['POST'])]
    public function getAvailableTimes(Request $request, ReservationRepository $reservationRepository): JsonResponse
    {
        // Récupère le jeton CSRF depuis les en-têtes
        $csrfToken = $request->headers->get('X-CSRF-TOKEN');

        // Vérifier la validité du jeton CSRF
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('', $csrfToken))) {
            return new JsonResponse(['error' => 'Jeton CSRF invalide.'], 403);
        }

        // Crée un objet DateTime à partir de la date de début
        $dateDebut = new \DateTime($request->request->get('dateDebut'));

        // Récupère les créneaux horaires disponibles
        $availabilities = $reservationRepository->findAllRDV($dateDebut);

        // Retourne les disponibilités sous forme de réponse JSON
        return new JsonResponse([
            'availabilities' => $availabilities,
        ]);
    }
}
