<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\SearchData;
use App\Form\SearchType;
use App\Repository\MatiereRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Matiere $matiere, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        return $this->render('home/index.html.twig', [
            'matieres' => $matieres,
            'matiere' => 'matiere',
        ]);
    }

    #[Route('/howItWorks', name: 'howItWorks')]
    public function howItWorks(Matiere $matiere, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        return $this->render('home/howItWorks.html.twig', [
            'matieres' => $matieres,
            'matiere' => 'matiere',
        ]);
    }

    #[Route('/pricing', name: 'pricing')]
    public function pricing(Matiere $matiere, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        return $this->render('home/pricing.html.twig', [
            'matieres' => $matieres,
            'matiere' => 'matiere',
        ]);
    }

    #[Route('/rules', name: 'rules')]
    public function rules(Matiere $matiere, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        return $this->render('home/rules.html.twig', [
            'matieres' => $matieres,
            'matiere' => 'matiere',
        ]);
    }


}
