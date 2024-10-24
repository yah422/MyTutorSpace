<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Form\RessourceType;
use App\Repository\MatiereRepository;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RessourceController extends AbstractController
{
    #[Route('/ressource', name: 'app_ressource')]
    public function index(MatiereRepository $matiereRepository, RessourceRepository $ressourceRepository): Response
    {
        $ressources = $ressourceRepository->findBy([],["titre" => "ASC"]);
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        return $this->render('ressource/index.html.twig', [
            'ressource' => 'ressource',
            'ressources' => $ressources,
            'matieres' => $matieres,

        ]);
    }

    // Méthode pour ajouter une Ressource
    #[Route('/ressource/ajouter', name: 'add_ressource')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(MatiereRepository $matiereRepository, Ressource $ressource, Request $request, EntityManagerInterface $entityManager): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        // Création d'une nouvelle instance de Ressource
        $ressource = new Ressource();

        // Création du formulaire
        $form = $this->createForm(RessourceType::class, $ressource);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde de la ressource en base de données
            $entityManager->persist($ressource);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Ressource ajoutée avec succès !');

            // Redirection vers la page de liste des ressources (à ajuster si nécessaire)
            return $this->redirectToRoute('app_ressource');
        }

        // Rendu de la vue avec le formulaire
        return $this->render('ressource/add.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);
    
    }

    #[Route('/ressource/supprimer/{id}', name: 'delete_ressource', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, CsrfTokenManagerInterface $csrfTokenManager, Ressource $ressource, EntityManagerInterface $entityManager,int $id): Response
    {
        if (!$ressource) {
            throw $this->createNotFoundException('No Ressource found for id ' . $id);
        }
     
        // Vérifier le token CSRF
        if ($this->isCsrfTokenValid('delete_ressource', $request->request->get('_token'))) {
            // Si valide, on peut supprimer la ressource
            $entityManager->remove($ressource);
            $entityManager->flush();

            // Redirection après suppression
            return $this->redirectToRoute('app_ressource');
        }
     
        // Si le token est invalide, lever une exception
        throw $this->createAccessDeniedException('Token CSRF invalide.');
        
    }

    #[Route('/ressource/{id}', name: 'show_ressource')]
    public function show(Ressource $ressource,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        return $this->render('ressource/show.html.twig', [
            'ressource' => $ressource,
            'matieres' => $matieres,
            
        ]);

    }





}
