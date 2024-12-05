<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsGenerator
{
    public function sendSms($message)
    {

        $sid = $_ENV['TWILIO_SID'] ?? null;
        $authToken = $_ENV['TWILIO_TOKEN'] ?? null; // Token d'authentification
        $from = $_ENV['TWILIO_FROM'] ?? null;
        $toNumber = $_ENV['ADMIN_TO'];

        if (!$sid || !$authToken || !$from || !$toNumber) {
            die("Les variables d'environnement ne sont pas définies correctement.");
        }
        
        // Crée une nouvelle instance de Client
        $client = new Client($sid, $authToken);
        $client->messages->create(
            // Numéro de téléphone de destination
            $toNumber,
            [
                'from' => $from,
                'body' => $message
            ]
        );
    }
}