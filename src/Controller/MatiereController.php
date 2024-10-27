<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MatiereController extends AbstractController
{
    #[Route('/matiere', name: 'app_matiere')]
    public function index(MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matieres,
        ]);
    }

    // Méthode pour ajouter une Matière
    #[Route('/matiere/ajouter', name: 'add_matiere')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(MatiereRepository $matiereRepository, Matiere $matiere, Request $request, EntityManagerInterface $entityManager): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        // Création d'une nouvelle instance de Matiere
        $matiere = new Matiere();

        // Création du formulaire
        $form = $this->createForm(MatiereType::class, $matiere);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde de la matière en base de données
            $entityManager->persist($matiere);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Matière ajoutée avec succès !');

            // Redirection vers la page de liste des matières (à ajuster si nécessaire)
            return $this->redirectToRoute('app_matiere');
        }

        // Rendu de la vue avec le formulaire
        return $this->render('matiere/add.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);

    }

    #[Route('/matiere/supprimer/{id}', name: 'delete_matiere', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, CsrfTokenManagerInterface $csrfTokenManager, Matiere $matiere, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$matiere) {
            throw $this->createNotFoundException('No matiere found for id ' . $id);
        }

        // Vérifier le token CSRF
        if ($this->isCsrfTokenValid('delete_matiere', $request->request->get('_token'))) {
            // Si valide, on peut supprimer la matière
            $entityManager->remove($matiere);
            $entityManager->flush();

            // Redirection après suppression
            return $this->redirectToRoute('app_matiere');
        }

        // Si le token est invalide, lever une exception
        throw $this->createAccessDeniedException('Token CSRF invalide.');

    }

    #[Route('/matiere/{id}/tuteurs', name: 'matiere_tuteurs')]
    public function getTuteursByMatiere(EntityManagerInterface $entityManager, Matiere $matiere): Response
    {
        $query = $entityManager->createQuery(
            'SELECT u
        FROM App\Entity\User u
        JOIN u.matieres m
        WHERE m = :matiere
        AND u.roles LIKE :role'
        )->setParameter('matiere', $matiere)
            ->setParameter('role', '%"ROLE_TUTEUR"%');

        $tuteurs = $query->getResult();

        return $this->render('matiere/tuteurs.html.twig', [
            'tuteurs' => $tuteurs,
            'matiere' => $matiere,
        ]);
    }

    #[Route('/matiere/{id}', name: 'show_matiere')]
    public function show(Matiere $matiere, MatiereRepository $matiereRepository, UserRepository $userRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $tuteurs = $userRepository->findTuteursByMatiere($matiere);
        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
            'matieres' => $matieres,
            'tuteurs' => $tuteurs, // Passer les tuteurs à la vue
        ]);

    }

}
