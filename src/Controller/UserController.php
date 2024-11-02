<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }  
    
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
    // #[IsGranted('ROLE_ADMIN')]
    public function add(MatiereRepository $matiereRepository, User $user, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Vérification du rôle 'ROLE_ADMIN'
        if (!$security->isGranted('ROLE_ADMIN')) {
        // Rediriger vers une page d'erreur si l'utilisateur n'a pas le rôle 'ROLE_ADMIN'
        return $this->render('user/errorPage.html.twig');     
        }
        
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
    

    #[Route('/user/edit/{id}', name: 'edit_user')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifiez si un nouveau mot de passe a été fourni
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
            }

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès !');
            return $this->redirectToRoute('app_show_user', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
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
