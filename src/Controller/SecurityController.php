<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType; // Utilisation du bon formulaire pour l'enregistrement
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/register/{role}', name: 'app_register_role')]
    public function registerRole(
        string $role, 
        Request $request, 
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $passwordHasher
    ): Response 
    {
        // Validation du rôle
        if (!in_array($role, ['eleve', 'tuteur', 'parent'])) {
            throw $this->createNotFoundException('Rôle non valide');
        }

        // Création du formulaire d'enregistrement
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($hashedPassword);

            // Définir le rôle spécifique
            switch ($role) {
                case 'eleve':
                    $user->setRoles(['ROLE_ELEVE']);
                    break;
                case 'tuteur':
                    $user->setRoles(['ROLE_TUTEUR']);
                    break;
                case 'parent':
                    $user->setRoles(['ROLE_PARENT']);
                    break;
            }

            // Enregistrer l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/form.html.twig', [
            'form' => $form->createView(),
            'role' => $role,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
