<?php

namespace App\Controller;

use App\Repository\MatiereRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        return $this->render('home/index.html.twig', [
            'matieres' => $matieres,
        ]);
    }
}
