<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private EmailVerifier $emailVerifier;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register/tuteur', name: 'app_register_tuteur')]
    public function registerTuteur(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        return $this->registerWithRole($request, $passwordHasher, $entityManager, 'ROLE_TUTEUR');
    }

    #[Route('/register/eleve', name: 'app_register_eleve')]
    public function registerEleve(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        return $this->registerWithRole($request, $passwordHasher, $entityManager, 'ROLE_ELEVE');
    }

    #[Route('/register/parent', name: 'app_register_parent')]
    public function registerParent(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        return $this->registerWithRole($request, $passwordHasher, $entityManager, 'ROLE_PARENT');
    }

    // Commun a tous les roles
    private function registerWithRole(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, string $role): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Assign the role based on the route
            $user->setRoles([$role]);

            // Save user to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to login or any other page
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/choix-role', name: 'app_role_choice')]
    public function roleChoice(): Response
    {
        return $this->render('registration/role_choice.html.twig');
    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
