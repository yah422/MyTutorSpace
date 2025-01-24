<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\JWTService;
use App\Services\EmailService;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private EmailVerifier $emailVerifier;

    public function __construct(EntityManagerInterface $entityManager,
     UserPasswordHasherInterface $passwordHasher, 
     EmailVerifier $emailVerifier)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register/tuteur', name: 'app_register_tuteur')]
    public function registerTuteur(Request $request, 
    SluggerInterface $slugger, 
    #[Autowire('%avatars_directory%')] string $avatarsDirectory, 
    JWTService $jwt, 
    EmailService $emailService
    ): Response
    {
        return $this->registerWithRole($request, 'ROLE_TUTEUR', $this->passwordHasher, $this->entityManager, $slugger, $jwt, $emailService, $avatarsDirectory);
    }

    #[Route('/register/eleve', name: 'app_register_eleve')]
    public function registerEleve(Request $request, 
    SluggerInterface $slugger, 
    #[Autowire('%avatars_directory%')] string $avatarsDirectory, 
    JWTService $jwt, 
    EmailService $emailService
    ): Response
    {
        return $this->registerWithRole($request, 'ROLE_ELEVE', $this->passwordHasher, $this->entityManager, $slugger, $jwt, $emailService, $avatarsDirectory);
    }

    #[Route('/register/parent', name: 'app_register_parent')]
    public function registerParent(Request $request, 
    SluggerInterface $slugger, 
    #[Autowire('%avatars_directory%')] string $avatarsDirectory, 
    JWTService $jwt, 
    EmailService $emailService
    ): Response
    {
        return $this->registerWithRole($request, 'ROLE_PARENT', $this->passwordHasher, $this->entityManager, $slugger, $jwt, $emailService, $avatarsDirectory);
    }

// Méthode commune pour tous les rôles
#[Route('/register/{role}', name: 'app_register')]
public function registerWithRole(
    Request $request,
    string $role,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager,
    SluggerInterface $slugger,
    JWTService $jwt,
    EmailService $emailService,
    #[Autowire('%avatars_directory%')] string $avatarsDirectory
): Response {
    $user = new User();
    
    // Passer le rôle à la création du formulaire
    $form = $this->createForm(RegistrationFormType::class, $user, [
        'role' => $role, // Passe le rôle ici
    ]);
    
    $form->handleRequest($request);

    // Si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion du mot de passe
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        // Gestion de la photo de profil
        $profilePicture = $form->get('image')->getData();
        if ($profilePicture) {
            $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePicture->guessExtension();

            try {
                $profilePicture->move($avatarsDirectory, $newFilename);
            } catch (FileException $e) {
                // Si l'upload rencontre un problème on affiche un message d'erreur
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
                // Et on redirige vers la même page avec le rôle sélectionné
                return $this->redirectToRoute('app_register', ['role' => $role]);
            }

            $user->setProfilePicture('/uploads/avatars/' . $newFilename);
        }

        // Récupérer la valeur de hourly_rate si le rôle est TUTEUR
        if ($role === 'ROLE_TUTEUR') {
            $hourlyRate = $form->get('hourly_rate')->getData();
            if ($hourlyRate === null) {
                // Valeur par défaut si non renseignée
                $hourlyRate = 20;
            }
            $user->setHourlyRate($hourlyRate);
        }

        // Attribution du rôle
        $user->setRoles([$role]);

        // Persist et flush
        $entityManager->persist($user);
        $entityManager->flush();

        // Générer le token JWT
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = [
            'user_id' => $user->getId()
        ];

        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // Envoi de l'email d'activation
        $emailService->sendRequestResetPassword(
            'no-reply@mytutorspace.fr',
            $user->getEmail(),
            'Activation de votre compte sur le site MyTutorSpace',
            'register',
            compact('user', 'token') // ['user' => $user, 'token'=>$token]
        );

        $this->addFlash('success', 'Utilisateur inscrit, veuillez cliquer sur le lien reçu pour confirmer votre adresse e-mail');
        $this->addFlash('success', 'Utilisateur inscrit. Veuillez vous connecter.');

        // Redirection vers la page de connexion
        return $this->redirectToRoute('app_login');
    }

    // Retourner la vue du formulaire
    return $this->render('registration/register.html.twig', [
        'registrationForm' => $form->createView(),
        'role' => $form->getConfig()->getOption('role'), // passer l'option a la  template
        'role' => $role, // passer le rôle à la vue si nécessaire
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
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        // On vérifie si le token est valide (cohérent, pas expiré et signature correcte)
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            // Le token est valide
            // On récupère les données (payload)
            $payload = $jwt->getPayload($token);

            // On récupère le user
            $user = $userRepository->find($payload['user_id']);

            // On vérifie qu'on a bien un user et qu'il n'est pas déjà activé
            if ($user && !$user->getIsVerified()) { // Correction ici : utiliser getIsVerified() au lieu de isVerified()
                $user->setIsVerified(true);
                $em->flush();

                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('home');
            }
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

}
