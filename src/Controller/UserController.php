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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function getAvailabilities(Request $request): Response
    {
        $start = new \DateTime($request->query->get('start'));
        $end = new \DateTime($request->query->get('end'));
        $tutor = $this->getUser();

        $availabilities = $this->calendarService->getTutorAvailabilities($tutor, $start, $end);

        $events = array_map(function ($availability) {
            return [
                'id' => $availability->getId(),
                'title' => 'Disponible',
                'start' => $availability->getStartTime()->format('Y-m-d\TH:i:s'),
                'end' => $availability->getEndTime()->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $availability->isBooked() ? '#EF4444' : '#10B981',
            ];
        }, $availabilities);

        return $this->json($events);
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
        NiveauRepository $niveauRepository
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
    public function edit(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $photoFilename = uniqid() . '.' . $photoFile->guessExtension();
                $photoFile->move(
                    $this->getParameter('photos_directory'),
                    $photoFilename
                );
                $user->setPhoto($photoFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');
            return $this->redirectToRoute('app_show_user', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
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
