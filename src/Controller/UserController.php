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

    #[Route('/user/admin/{id}', name: 'app_user_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminProfile(User $user, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/admin.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/parent/{id}', name: 'app_user_parent')]
    #[IsGranted('ROLE_PARENT')]
    public function parentProfile(User $user, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/parent.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/eleve/{id}', name: 'app_user_eleve')]
    #[IsGranted('ROLE_ELEVE')]
    public function eleveProfile(User $user, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/eleve.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
        ]);
    }

    #[Route('/user/tuteur/{id}', name: 'app_user_tuteur')]
    #[IsGranted('ROLE_TUTEUR')]
    public function tuteurProfile(User $user, MatiereRepository $matiereRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('user/tuteur.html.twig', [
            'user' => $user,
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

}
