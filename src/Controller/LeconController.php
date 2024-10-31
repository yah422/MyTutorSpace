<?php

namespace App\Controller;

use id;
use App\Entity\Lecon;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Form\LeconType;
use App\Repository\LeconRepository;
use App\Repository\MatiereRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LeconController extends AbstractController
{
    #[Route('/lecon', name: 'app_lecon')]
    public function index(LeconRepository $leconRepository, MatiereRepository $matiereRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupération des matières triées par nom
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);

        // Récupération des niveaux avec leurs leçons
        $niveauRepository = $entityManager->getRepository(Niveau::class);
        $leconsParNiveau = $niveauRepository->findAll();

        // Récupération des leçons triées par titre
        $lecons = $leconRepository->findBy([], ["titre" => "ASC"]);

        return $this->render('lecon/index.html.twig', [
            'lecon' => 'lecon',
            'lecons' => $lecons,
            'matieres' => $matieres,
            'leconsParNiveau' => $leconsParNiveau,
        ]);
    }


    #[Route('/lecon/ajouter', name: 'add_lecon')]
    // #[IsGranted('ROLE_ADMIN')]
    public function add(MatiereRepository $matiereRepository, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Vérification des rôles 'ROLE_ADMIN' ou 'ROLE_TUTEUR'
        if (!$security->isGranted('ROLE_ADMIN') && !$security->isGranted('ROLE_TUTEUR')) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas les rôles requis
            return $this->render('user/errorPage.html.twig');     
        }

        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        // Création d'une nouvelle instance de Lecon
        $lecon = new Lecon();

        // Création du formulaire
        $form = $this->createForm(LeconType::class, $lecon);
        $form->handleRequest($request);

        // Sauvegarde de la leçon si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lecon);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Leçon ajoutée avec succès !');

            return $this->redirectToRoute('app_lecon');
        }

        return $this->render('lecon/add.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);
    }

    #[Route('/lecon/supprimer/{id}', name: 'delete_lecon', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, CsrfTokenManagerInterface $csrfTokenManager, Lecon $lecon, EntityManagerInterface $entityManager): Response
    {
        if (!$lecon) {
            throw $this->createNotFoundException('Aucune leçon trouvée pour cet identifiant.');
        }

        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete_lecon', $request->request->get('_token'))) {
            $entityManager->remove($lecon);
            $entityManager->flush();

            $this->addFlash('success', 'Leçon supprimée avec succès !');

            return $this->redirectToRoute('app_lecon');
        }

        throw $this->createAccessDeniedException('Token CSRF invalide.');
    }

    #[Route('/lecon/matiere/{id}', name: 'lecons_par_matiere')]
    public function leconsParMatiere(Matiere $matiere, MatiereRepository $matiereRepository, int $id, EntityManagerInterface $entityManager, Lecon $lecon): Response
    {
        // Récupération des matières triées par nom
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);

        // Récupération des leçons par matière
        $query = $entityManager->createQuery(
            'SELECT l
            FROM App\Entity\Lecon l
            JOIN l.matiere m
            WHERE m.id = :id'
        )->setParameter('id', $id);
    
        // Exécution de la requête
        $lecons = $query->getResult();
    
        // Rendu du template
        return $this->render('lecon/show.html.twig', [
            'lecons' => $lecons, // Liste des leçons
            'lecon' => $lecon,
            'matieres' => $matieres,
            'matiere' => $matiere,

        ]);
    }

    #[Route('/lecon/{id}', name: 'show_lecon')]
    public function show(Lecon $lecon, MatiereRepository $matiereRepository, ExerciceRepository $exerciceRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $exercices = $exerciceRepository->findBy([], ["titre" => "ASC"]);
        $matiere = $lecon->getMatiere(); // Assumes there's a method in Lecon to get its Matiere
    
        return $this->render('lecon/detail.html.twig', [
            'lecon' => $lecon,
            'exercices' => $exercices,
            'matieres' => $matieres,
            'matiere' => $matiere,
        ]);
    }
    
}
