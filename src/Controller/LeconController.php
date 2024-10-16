<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LeconController extends AbstractController
{
    #[Route('/lecon', name: 'app_lecon')]
    public function index(): Response
    {
        return $this->render('lecon/index.html.twig', [
            'controller_name' => 'LeconController',
        ]);
    }
}
