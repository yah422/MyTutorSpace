<?php

namespace App\Controller;

use App\Form\SeanceType;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Seance;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProgressionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/suivi')]
class SuiviController extends AbstractController
{
    #[Route('/eleve/{id}', name: 'app_suivi_eleve')]
    public function suiviEleve(
        User $eleve,
        SeanceRepository $seanceRepo,
        ProgressionRepository $progressionRepo
    ): Response {
        // Récupérer les séances de l'élève
        $seances = $seanceRepo->findBy(['eleve' => $eleve], ['date' => 'DESC']);

        // Récupérer les progressions par matière
        $progressions = $progressionRepo->findByEleve($eleve);

        return $this->render('suivi/eleve.html.twig', [
            'eleve' => $eleve,
            'seances' => $seances,
            'progressions' => $progressions
        ]);
    }

    #[Route('/seance/nouvelle', name: 'app_seance_new')]
    public function nouvelleSeance(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seance);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_eleve', ['id' => $seance->getEleve()->getId()]);
        }

        return $this->render('suivi/nouvelle_seance.html.twig', [
            'form' => $form->createView()
        ]);
    }
}