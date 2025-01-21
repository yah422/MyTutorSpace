<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\SauvegardeProfil;
use App\Repository\UserRepository;
use App\Repository\NiveauRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Calendar\CalendarService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TutorAvailabilityRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
        NiveauRepository $niveauRepository,
        PaginatorInterface $paginator, // Injection du pagineur
        User $user
    ): Response {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);

        $selectedMatiereId = $request->query->get('matiere', null);
        $selectedNiveauId = $request->query->get('niveau', null);
        $selectedPrix = $request->query->get('hourly_rate', null);

        $selectedPrix = $selectedPrix !== null && $selectedPrix >= 0 ? (float) $selectedPrix : null;

        $selectedMatiere = $selectedMatiereId ? $matiereRepository->find($selectedMatiereId) : null;
        $selectedNiveau = $selectedNiveauId ? $niveauRepository->find($selectedNiveauId) : null;

        $data = $userRepository->findTutorsByFilters($selectedMatiere, $selectedNiveau, $selectedPrix);

        $page = $request->query->getInt('page', 1); // Récupération de la page actuelle
        $tuteurs = $paginator->paginate(
            $data, // Data pour la pagination
            $page,  // Numéro de la page
            4     // Nombre d'éléments par page
        );

        return $this->render('user/index.html.twig', [
            'matieres' => $matieres,
            'niveaux' => $niveaux,
            'selectedMatiereId' => $selectedMatiereId,
            'selectedNiveauId' => $selectedNiveauId,
            'selectedPrix' => $selectedPrix ?? '',
            'tuteurs' => $tuteurs,
            'user' => $user,
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

            // Récupérer la valeur de hourly_rate
            $hourlyRate = $form->get('hourly_rate')->getData();

            if ($hourlyRate === null) {
                // Attribuer une valeur par défaut si hourly_rate est NULL
                $hourlyRate = 20;
            }

            $user->setHourlyRate($hourlyRate);

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

    #[Route('/user/edit/{id}', name: 'edit_user', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%avatars_directory%')] string $avatarsDirectory
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

                    //Et on redirige vers le formulaire
                    return $this->redirectToRoute('app_register');
                }
                $user->setProfilePicture($newFilename);
            }

            try {
                $entityManager->flush();
                $this->addFlash('success', 'Profil mis à jour avec succès');
                return $this->redirectToRoute('app_show_user', ['id' => $user->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la mise à jour du profil.');
            }
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'role' => $user->getRoles(),
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('user/dashboardUserLogin.html.twig');
    }

    #[Route('/user/ban/{id}', name: 'app_ban_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function ban(User $user, int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);
        // Bannir l'utilisateur
        $user->setBanned(true);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur a été verrouillé.');

        // Redirection vers le détail du user après l'action
        return $this->redirectToRoute('app_show_user', ['id' => $user->getId()]);
    }

    #[Route('/user/unban/{id}', name: 'app_unban_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function unban(User $user, int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);
        // UnBan the user
        $user->setBanned(false);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur a été banni.');

        // Redirection vers le détail de user après l'action
        return $this->redirectToRoute('app_show_user', ['id' => $user->getId()]);
    }

    #[Route('/user/{id}', name: 'app_show_user')]
    public function profile(
        User $user,
        MatiereRepository $matiereRepository,
        NiveauRepository $niveauRepository,
        EntityManagerInterface $em
    ): Response {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);

        $tuteur = null;

        $existingSauvegarde = $em->getRepository(SauvegardeProfil::class)
            ->findOneBy(['user' => $user, 'tuteur' => $tuteur]);

        if ($existingSauvegarde) {
            $this->addFlash('warning', 'Ce profil est déjà sauvegardé.');
            return $this->redirectToRoute('app_show_user', ['id' => $user->getId()]);
        }

        if (in_array('ROLE_TUTEUR', $user->getRoles(), true)) {
            $tuteur = $user; // L'utilisateur est lui-même le tuteur
        }

        $this->addFlash('success', 'Votre profil a été sauvegardé.');
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
            'niveaux' => $niveaux,
            'tuteur' => $tuteur,
        ]);
    }

}
