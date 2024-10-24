<?php

namespace App\Controller;

use App\Entity\Lecon;
use App\Form\LeconType;
use App\Repository\LeconRepository;
use App\Repository\MatiereRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
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

    
    // Méthode pour ajouter une Leçon
    #[Route('/lecon/ajouter', name: 'add_lecon')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(MatiereRepository $matiereRepository,Lecon $lecon, Request $request, EntityManagerInterface $entityManager): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        // Création d'une nouvelle instance de Matiere
        $categorie = new lecon();

        // Création du formulaire
        $form = $this->createForm(LeconType::class, $lecon);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde de la matière en base de données
            $entityManager->persist($lecon);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Leçon ajoutée avec succès !');

            // Redirection vers la page de liste des leçons (à ajuster si nécessaire)
            return $this->redirectToRoute('app_lecon');
        }

        // Rendu de la vue avec le formulaire
        return $this->render('lecon/add.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,

        ]);
    
    }

    #[Route('/lecon/supprimer/{id}', name: 'delete_lecon', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, CsrfTokenManagerInterface $csrfTokenManager, lecon $lecon, EntityManagerInterface $entityManager,int $id): Response
    {
        if (!$lecon) {
            throw $this->createNotFoundException('No leçon found for id ' . $id);
        }
     
        // Vérifier le token CSRF
        if ($this->isCsrfTokenValid('delete_lecon', $request->request->get('_token'))) {
            // Si valide, on peut supprimer la categorie
            $entityManager->remove($lecon);
            $entityManager->flush();

            // Redirection après suppression
            return $this->redirectToRoute('app_lecon');
        }
     
        // Si le token est invalide, lever une exception
        throw $this->createAccessDeniedException('Token CSRF invalide.');
        
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
