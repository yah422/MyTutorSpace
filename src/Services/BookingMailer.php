<?php

namespace App\Services;

use App\Entity\TutoringBooking;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class BookingMailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmail(TutoringBooking $booking): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@votresite.com')
            ->to($booking->getStudentEmail())
            ->subject('Confirmation de votre réservation de tutorat')
            ->htmlTemplate('emails/booking_confirmation.html.twig')
            ->context([
                'booking' => $booking
            ]);

        $this->mailer->send($email);

        // Send notification to tutor
        $tutorEmail = (new TemplatedEmail())
            ->from('noreply@votresite.com')
            ->to($booking->getTuteur()->getEmail())
            ->subject('Nouvelle réservation de tutorat')
            ->htmlTemplate('emails/tutor_notification.html.twig')
            ->context([
                'booking' => $booking
            ]);

        $this->mailer->send($tutorEmail);
    }
}
