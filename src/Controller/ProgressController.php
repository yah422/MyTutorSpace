<?php

namespace App\Controller;

use App\Entity\Progress;
use App\Form\ProgressType;
use App\Repository\ProgressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgressController extends AbstractController
{
    #[Route('/progress', name: 'app_progress_index', methods: ['GET'])]
    public function index(ProgressRepository $progressRepository): Response
    {
        // Vérifie si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifie si l'utilisateur a le rôle approprié
        $this->denyAccessUnlessGranted('ROLE_PARENT');

        // Vérifie que l'utilisateur a des "dependents"
        $dependents = $user->getDependents();
        if (!$dependents) {
            $this->addFlash('warning', 'Vous n\'avez aucun dependent enregistré.');
            return $this->redirectToRoute('app_dashboard'); // Redirection si nécessaire
        }

        // Récupère les enregistrements de progrès
        $progressRecords = $progressRepository->findBy(['dependent' => $dependents]);

        return $this->render('progress/index.html.twig', [
            'progressRecords' => $progressRecords,
        ]);
    }

    #[Route('/progress/new', name: 'app_progress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur a le rôle de tuteur
        $this->denyAccessUnlessGranted('ROLE_TUTOR');

        $progress = new Progress();
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($progress);
            $entityManager->flush();

            $this->addFlash('success', 'Progrès ajouté avec succès.');
            return $this->redirectToRoute('app_progress_index');
        }

        return $this->render('progress/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/progress/{id}', name: 'app_progress_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Progress $progress): Response
    {
        // Vérifie si l'utilisateur a le rôle approprié
        if (!$this->isGranted('ROLE_PARENT') && !$this->isGranted('ROLE_TUTOR')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        return $this->render('progress/show.html.twig', [
            'progress' => $progress,
        ]);
    }
}
