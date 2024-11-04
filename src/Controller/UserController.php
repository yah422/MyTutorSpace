<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\MatiereRepository;
use App\Repository\NiveauRepository;
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
    public function index(
        Request $request, 
        UserRepository $userRepository,
        MatiereRepository $matiereRepository,
        NiveauRepository $niveauRepository
    ): Response {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);
    
        // Initialisation avec des valeurs par défaut
        $selectedMatiereId = $request->query->get('matiere', null);
        $selectedNiveauId = $request->query->get('niveau', null);
    
        $selectedMatiere = $selectedMatiereId ? $matiereRepository->find($selectedMatiereId) : null;
        $selectedNiveau = $selectedNiveauId ? $niveauRepository->find($selectedNiveauId) : null;
    
        $tuteurs = $userRepository->findTutorsByFilters($selectedMatiere, $selectedNiveau);
    
        return $this->render('user/index.html.twig', [
            'matieres' => $matieres,
            'niveaux' => $niveaux,
            'selectedMatiereId' => $selectedMatiereId,
            'selectedNiveauId' => $selectedNiveauId,
            'tuteurs' => $tuteurs,
        ]);
    }
    

    #[Route('/user/ajouter', name: 'add_user')]
    public function add(
        Request $request, 
        EntityManagerInterface $entityManager,
        Security $security,
        MatiereRepository $matiereRepository,
        NiveauRepository $niveauRepository
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->render('user/errorPage.html.twig');     
        }
        
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
            'niveaux' => $niveaux,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'edit_user')]
    public function edit(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        MatiereRepository $matiereRepository,
        NiveauRepository $niveauRepository
    ): Response {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
            'matieres' => $matieres,
            'niveaux' => $niveaux,
        ]);
    }

    #[Route('/user/{id}', name: 'app_show_user')]
    public function profile(
        User $user,
        MatiereRepository $matiereRepository,
        NiveauRepository $niveauRepository
    ): Response {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);
        
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
            'niveaux' => $niveaux,
        ]);
    }
}