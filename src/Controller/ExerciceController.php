<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Repository\MatiereRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExerciceController extends AbstractController
{
    #[Route('/exercice', name: 'app_exercice')]
    public function index(ExerciceRepository $exerciceRepository,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        $exercices = $exerciceRepository->findBy([],["titre" => "ASC"]);
        return $this->render('exercice/index.html.twig', [
            'exercice' => 'exercice',
            'exercices' => $exercices,
            'matieres' => $matieres,

        ]);
    }

    // Méthode pour ajouter un Exercice
    #[Route('/exercice/ajouter', name: 'add_exercice')]
    // #[IsGranted('ROLE_TUTEUR')]
    public function add(exercice $exercice, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Vérification des rôles 'ROLE_ADMIN' ou 'ROLE_TUTEUR'
        if (!$security->isGranted('ROLE_ADMIN') && !$security->isGranted('ROLE_TUTEUR')) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas les rôles requis
            return $this->render('user/errorPage.html.twig');     
        }

        // Création d'une nouvelle instance de Matiere
        $categorie = new Exercice();

        // Création du formulaire
        $form = $this->createForm(ExerciceType::class, $exercice);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde de l'exercice en base de données
            $entityManager->persist($exercice);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Exercice ajoutée avec succès !');

            // Redirection vers la page de liste des exercices (à ajuster si nécessaire)
            return $this->redirectToRoute('app_exercice');
        }

        // Rendu de la vue avec le formulaire
        return $this->render('exercice/add.html.twig', [
            'form' => $form->createView(),
        ]);
    
    }

    #[Route('/exercice/{id}', name: 'show_exercice')]
    public function show(Exercice $exercice,ExerciceRepository $exerciceRepository,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);
        $exercices = $exerciceRepository->findBy([],["titre" => "ASC"]);
        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
            'exercices' => $exercices,
            'matieres' => $matieres,

            
        ]);

    }
}
