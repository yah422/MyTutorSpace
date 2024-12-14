<?php

namespace App\Service;

use App\Entity\TutoringBooking;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailNotificationService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendBookingConfirmationToStudent(TutoringBooking $booking): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@tutoring.com')
            ->to($booking->getStudentEmail())
            ->subject('Confirmation de votre réservation de cours')
            ->htmlTemplate('emails/booking_confirmation_student.html.twig')
            ->context([
                'booking' => $booking
            ]);

        $this->mailer->send($email);
    }

    public function sendBookingNotificationToTutor(TutoringBooking $booking): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@tutoring.com')
            ->to($booking->getTuteur()->getEmail())
            ->subject('Nouvelle demande de cours')
            ->htmlTemplate('emails/booking_notification_tutor.html.twig')
            ->context([
                'booking' => $booking
            ]);

        $this->mailer->send($email);
    }
}