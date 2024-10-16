<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciceController extends AbstractController
{
    #[Route('/exercice', name: 'app_exercice')]
    public function index(): Response
    {
        return $this->render('exercice/index.html.twig', [
            'controller_name' => 'ExerciceController',
        ]);
    }
}
