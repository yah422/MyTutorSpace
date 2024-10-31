<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/index.html.twig', [
            'matieres' => $matieres,
        ]);
    }

    

    // Méthode pour ajouter une Ressource
    #[Route('/user/ajouter', name: 'add_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(MatiereRepository $matiereRepository, User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        // Création d'une nouvelle instance de User
        $user = new User();

        // Création du formulaire
        $form = $this->createForm(UserType::class, $user);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde de la ressource en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Utilisateur ajoutée avec succès !');

            // Redirection vers la page de liste des user (à ajuster si nécessaire)
            return $this->redirectToRoute('app_user');
        }

        // Rendu de la vue avec le formulaire
        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);
    }
    

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traiter le mot de passe uniquement s'il a été changé
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    password_hash($plainPassword, PASSWORD_BCRYPT)
                );
            }

            $em->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]); // Correction du nom de route
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);
    }
    #[Route('/user/{id}', name: 'app_show_user')]
    public function profile(User $user, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }
}
