<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Entity\Contact;
use App\Entity\TutoringBooking;
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

    // confirmation du contact à l'user
    public function sendConfirmationEmail(MailerInterface $mailer, string $to, Contact $contact): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@mytutorspace.com')
            ->to($to)
            ->subject('Confirmation de votre message')
            ->htmlTemplate('emails/contactConfirmation.html.twig')
            ->context([
                'contact' => $contact
            ]);

        $mailer->send($email);
    }



    // notification à l'administrateur de la prise de contact
    public function sendAdminNotificationEmail(MailerInterface $mailer, Contact $contact): void
    {
        $adminEmail = 'admin@mytutorspace.com';
        $emailContent = $this->renderView('emails/adminContactNotification.html.twig', [
            'contact' => $contact
        ]);

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@mytutorspace.com', 'MyTutorSpace'))
            ->to($adminEmail)
            ->subject('Nouvelle demande de contact')
            ->html($emailContent);

        $mailer->send($email);
    }

    public function sendRequestResetPassword(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void //La fonction ne retourne rien donc -> void
    {
        //Création du mail
        $email = (new TemplatedEmail()) //Cette classe permet de rajouter les informations nécessaire à l\'envoie du mail (expediteur, destinaire)
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        //Envoie du mail
        $this->mailer->send($email);

    }
}