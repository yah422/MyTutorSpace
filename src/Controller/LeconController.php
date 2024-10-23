<?php

namespace App\Controller;

use App\Entity\Lecon;
use App\Repository\LeconRepository;
use App\Repository\MatiereRepository;
use App\Repository\ExerciceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LeconController extends AbstractController
{


    #[Route('/lecon', name: 'app_lecon')]
    public function index(LeconRepository $leconRepository,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        $lecons = $leconRepository->findBy([],["titre" => "ASC"]);
        return $this->render('lecon/index.html.twig', [
            'lecon' => 'lecon',
            'lecons' => $lecons,
            'matieres' => $matieres,

        ]);
    }

    #[Route('/lecon/{id}', name: 'show_lecon')]
    public function show(Lecon $lecon,ExerciceRepository $exerciceRepository,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        // Récupérer les exercices associés à cette leçon
        $exercices = $exerciceRepository->findBy([],["titre" => "ASC"]);
        return $this->render('lecon/show.html.twig', [
            'lecon' => $lecon,
            'exercices' => $exercices,
            'matieres' => $matieres,

        ]);
    }
}
