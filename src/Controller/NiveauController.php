<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Form\NiveauType;
use App\Repository\NiveauRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NiveauController extends AbstractController
{
    #[Route('/niveau', name: 'app_niveau')]
    public function index(NiveauRepository $niveauRepository, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        $niveaux =$niveauRepository->findBy([], ["titre" => "ASC"]);
        return $this->render('niveau/index.html.twig', [
            'niveaux' => $niveaux,
            'matieres' => $matieres,

        ]);
    }

    // Méthode pour ajouter un Niveau
    #[Route('/niveau/ajouter', name: 'add_niveau')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(NiveauRepository $niveauRepository, Niveau $niveau, Request $request, EntityManagerInterface $entityManager, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        $niveaux =$niveauRepository->findBy([], ["titre" => "ASC"]);
        // Création d'une nouvelle instance de Niveau
        $niveau = new Niveau();

        // Création du formulaire
        $form = $this->createForm(NiveauType::class, $niveau);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde du niveau en base de données
            $entityManager->persist($niveau);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Niveau ajoutée avec succès !');

            // Redirection vers la page de liste des niveaux
            return $this->redirectToRoute('app_niveau');
        }

        // Rendu de la vue avec le formulaire
        return $this->render('niveau/add.html.twig', [
            'form' => $form->createView(),
            'niveaux' => $niveaux,
            'matieres' => $matieres,
        ]);

    }

    #[Route('/niveau/supprimer/{id}', name: 'delete_niveau', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, CsrfTokenManagerInterface $csrfTokenManager, Niveau $niveau, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$niveau) {
            throw $this->createNotFoundException('No niveau found for id ' . $id);
        }

        // Vérifier le token CSRF
        if ($this->isCsrfTokenValid('delete_niveau', $request->request->get('_token'))) {
            // Si valide, on peut supprimer le niveau
            $entityManager->remove($niveau);
            $entityManager->flush();

            // Redirection après suppression
            return $this->redirectToRoute('app_niveau');
        }

        // Si le token est invalide, lever une exception
        throw $this->createAccessDeniedException('Token CSRF invalide.');

    }

    #[Route('/niveau/{id}', name: 'show_niveau')]
    public function show(NiveauRepository $niveauRepository, Niveau $niveau, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        $niveaux =$niveauRepository->findBy([], ["titre" => "ASC"]);
        return $this->render('niveau/show.html.twig', [
            'niveau' => $niveau,
            'niveaux' => $niveaux,
            'matieres' => $matieres,
            'lecons' => $niveau->getLecons(), // relation ManyToMany
        ]);

    }

}
