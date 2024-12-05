<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Reservation;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailService extends AbstractController
{
    private $mailer;
    private $templating;

    public function __construct(MailerInterface $mailer, \Twig\Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }


    // confirmation de prise de RDV pour le client
    public function sendConfirmationEmailTo(MailerInterface $mailer, string $emailAddress, \DateTime $startDate): void
    {
        $emailContent = $this->renderView('emails/appointment_confirmation.html.twig', [
            'appointmentDate' => $startDate->format('d/m/Y à H:i')
        ]);

        $email = (new TemplatedEmail())
            ->from(new Address('admin@mytutorspace.com', 'MyTutorSpace'))
            ->to($emailAddress)
            ->subject('Confirmation de votre rendez-vous')
            ->html($emailContent);

        $mailer->send($email);
    }

    // confirmation de prise de RDV
    public function sendConfirmationEmailFrom(MailerInterface $mailer, string $emailAddress, \DateTime $startDate): void
    {
        $emailContent = $this->renderView('emails/appointment_confirmation.html.twig', [
            'appointmentDate' => $startDate->format('d/m/Y à H:i')
        ]);

        $email = (new TemplatedEmail())
            ->from(new Address($emailAddress))
            ->to(new Address('admin@mytutorspace.com', 'MyTutorSpace'))
            ->subject('Nouveau Rendez vous')
            ->html($emailContent);

        $mailer->send($email);
    }


    // confirmation du contact à l'user
    public function sendConfirmationEmail(MailerInterface $mailer, string $emailAddress, Contact $contact): void
    {
        $emailContent = $this->renderView('emails/contact_confirmation.html.twig');

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@mytutorspace.com', 'MyTutorSpace'))
            ->to($emailAddress)
            ->subject('Confirmation de prise de contact')
            ->html($emailContent);

        $mailer->send($email);
    }


    // notification à l'administrateur de la prise de contact
    public function sendAdminNotificationEmail(MailerInterface $mailer, Contact $contact): void
    {
        $adminEmail = 'admin@mytutorspace.com';
        $emailContent = $this->renderView('emails/admin_contact_notification.html.twig', [
            'contact' => $contact
        ]);

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@mytutorspace.com', 'MyTutorSpace'))
            ->to($adminEmail)
            ->subject('Nouvelle demande de contact')
            ->html($emailContent);

        $mailer->send($email);
    }
    

    // notification de réception d'un nouveau message
    public function notificationEmailToRecipient(MailerInterface $mailer, string $emailAddress, $message): void
    {
        $emailContent = $this->renderView('emails/message_notification.html.twig', [
            'titreMessage' => $message->getTitre(),
            'contenuMessage' => $message->getContenu(),
            'dateMessage' => $message->getCreatedAt()->format('d/m/Y à H:i')
        ]);

        $email = (new TemplatedEmail())
            ->from(new Address('admin@mytutorspace.com', 'MyTutorSpace'))
            ->to($emailAddress)
            ->subject('Nouveau message reçu')
            ->html($emailContent);

        $mailer->send($email);
    }

    // notification d'annulation de RDV
    public function sendCancellationEmail(MailerInterface $mailer, Reservation $reservation): void
    {
        $user = $reservation->getUser();
        $emailContent = $this->renderView('emails/appointment_cancellation.html.twig', [
            'dateReservation' => $reservation->getDateDebut()->format('d/m/Y à H:i'),
            'prenom' => $user ? $user->getPrenom() : 'Utilisateur anonyme',
        ]);

        $email = (new Email())
            ->from(new Address('no-reply@mytutorspace.com', 'MyTutorSpace'))
            ->to('admin@mytutorspace.com')
            ->subject('Annulation de rendez-vous')
            ->html($emailContent);

        $mailer->send($email);
    }


    // notification de suppression de compte
    public function sendAccountDeletionEmail(MailerInterface $mailer, UserInterface $user): void
    {
        /**
         * @var User|null $user
         */
        $emailContent = $this->renderView('emails/account_deletion.html.twig', [
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
        ]);

        $email = (new Email())
            ->from(new Address('no-reply@mytutorspace.com', 'MyTutorSpace'))
            ->to('admin@mytutorspace.com')
            ->subject('Suppression de compte utilisateur')
            ->html($emailContent);

        $mailer->send($email);
    }
}