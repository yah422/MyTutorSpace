<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Services\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    private $htmlSanitizer;
    private $emailService;

    public function __construct(HtmlSanitizerInterface $htmlSanitizer, EmailService $emailService)
    {
        $this->htmlSanitizer = $htmlSanitizer;
        $this->emailService = $emailService;
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        MailerInterface $mailer,
        EmailService $emailService,
    ): Response {
        $contact = new Contact();
        // Crée le formulaire de contact
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact = $form->getData();

            // Sanitize les champs
            $contact->setNom($this->htmlSanitizer->sanitize($contact->getNom()));
            $contact->setMessage($this->htmlSanitizer->sanitize($contact->getMessage()));

            // Vérifie le sujet et utilise une valeur par défaut
            $sujet = $contact->getSujet();
            if ($sujet !== null) {
                $contact->setSujet($this->htmlSanitizer->sanitize($sujet));
            }

            // Vérifie si l'adresse email est valide
            $emailAddress = $contact->getEmail();
            if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                $this->addFlash('error', 'Adresse email invalide.');
                return $this->redirectToRoute('app_reservation');
            }

            //Vérifie si un utilisateur est connecter
            $user = $security->getUser();

            // Si un utilisateur est connecter, il est assigné au contact
            if ($user) {
                $contact->setUser($user);
            }

            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoie un email de confirmation à l'utilisateur
            $emailService->sendConfirmationEmail($mailer, $emailAddress, $contact);
            // Envoie une notification à l'admin
            $emailService->sendAdminNotificationEmail($mailer, $contact);

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('home');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}