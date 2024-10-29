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
        // Cette vue pourrait lister tous les utilisateurs ou permettre d'accéder aux profils par rôle -- à voir ?
        return $this->render('user/index.html.twig', [
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/admin/{id}', name: 'app_user_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminProfile(User $user,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/admin.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/parent/{id}', name: 'app_user_parent')]
    public function parentProfile(User $user,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/parent.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/eleve/{id}', name: 'app_user_eleve')]
    public function eleveProfile(User $user,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/eleve.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/tuteur/{id}', name: 'app_user_tuteur')]
    public function tuteurProfile(User $user,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/tuteur.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function editProfile(User $user, Request $request, EntityManagerInterface $entityManager,MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès');

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);
    }
}
