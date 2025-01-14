<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\NiveauRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Calendar\CalendarService;
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
    private CalendarService $calendarService;

    public function __construct(UserPasswordHasherInterface $passwordHasher, CalendarService $calendarService)
    {
        $this->passwordHasher = $passwordHasher;
        $this->calendarService = $calendarService;
    }

    #[Route('/calendar', name: 'app_tutor_calendar')]
    #[IsGranted('ROLE_TUTEUR')]
    public function calendar(): Response
    {
        return $this->render('tutor/calendar.html.twig');
    }

    #[Route('/availabilities', name: 'app_tutor_availabilities', methods: ['GET'])]
    #[IsGranted('ROLE_TUTEUR')]
    public function getAvailabilities(Request $request, CalendarService $calendarService): JsonResponse
    {
        $user = $this->getUser();
        $startDate = new \DateTime($request->query->get('start'));
        $endDate = new \DateTime($request->query->get('end'));

        try {
            $availabilities = $calendarService->getTutorAvailabilities($user, $startDate, $endDate);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json($availabilities);
    }

    #[Route('/availability/add', name: 'app_tutor_availability_add', methods: ['POST'])]
    #[IsGranted('ROLE_TUTEUR')]
    public function addAvailability(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $tutor = $this->getUser();

        $startTime = new \DateTime($data['start']);
        $endTime = new \DateTime($data['end']);

        $availability = $this->calendarService->addAvailability($tutor, $startTime, $endTime);

        return $this->json([
            'id' => $availability->getId(),
            'start' => $availability->getStartTime()->format('Y-m-d\TH:i:s'),
            'end' => $availability->getEndTime()->format('Y-m-d\TH:i:s'),
        ]);
    }

    #[Route('/user', name: 'app_user')]
    public function index(
        Request $request,
        UserRepository $userRepository,
        MatiereRepository $matiereRepository,
        NiveauRepository $niveauRepository,
        User $user,
    ): Response {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);

        $selectedMatiereId = $request->query->get('matiere', null);
        $selectedNiveauId = $request->query->get('niveau', null);

        $selectedMatiere = $selectedMatiereId ? $matiereRepository->find($selectedMatiereId) : null;
        $selectedNiveau = $selectedNiveauId ? $niveauRepository->find($selectedNiveauId) : null;

        $tuteurs = $userRepository->findTutorsByFilters($selectedMatiere, $selectedNiveau);

        return $this->render('user/index.html.twig', [
            'matieres' => $matieres,
            'user' => $user,
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
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('user/dashboardUserLogin.html.twig');
    }

    #[Route('/user/{id}', name: 'app_show_user')]
    public function profile(
        User $user,
        MatiereRepository $matiereRepository,
        NiveauRepository $niveauRepository
    ): Response {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveaux = $niveauRepository->findBy([], ["titre" => "ASC"]);

        $tuteur = null;

        if (in_array('ROLE_TUTEUR', $user->getRoles(), true)) {
            $tuteur = $user; // L'utilisateur est lui-même le tuteur
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'matieres' => $matieres,
            'niveaux' => $niveaux,
            'tuteur' => $tuteur,
        ]);
    }
}
