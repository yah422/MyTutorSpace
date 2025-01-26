<?php

namespace App\Controller;

use App\Entity\TutorAvailability;
use App\Form\TutorAvailabilityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TutorAvailabilityRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tutor/availability')]
class TutorAvailabilityController extends AbstractController
{
    #[Route('/', name: 'tutor_availability_index', methods: ['GET'])]
    #[IsGranted('ROLE_TUTEUR')]
    public function index(TutorAvailabilityRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TUTEUR');

        return $this->render('tutor_availability/index.html.twig', [
            'availabilities' => $repository->findBy(['tutor' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'tutor_availability_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TUTEUR')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TUTEUR');

        $availability = new TutorAvailability();
        $availability->setTutor($this->getUser());

        $form = $this->createForm(TutorAvailabilityType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($availability);
            $entityManager->flush();

            return $this->redirectToRoute('tutor_availability_index');
        }

        return $this->render('tutor_availability/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}/edit', name: 'tutor_availability_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TUTEUR')]
    public function edit(Request $request, TutorAvailability $availability, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TUTEUR');

        if ($availability->getTutor() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(TutorAvailabilityType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tutor_availability_index');
        }

        return $this->render('tutor_availability/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'tutor_availability_delete', methods: ['POST'])]
    #[IsGranted('ROLE_TUTEUR')]
    public function delete(Request $request, TutorAvailability $availability, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TUTEUR');

        if ($availability->getTutor() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $availability->getId(), $request->request->get('_token'))) {
            $entityManager->remove($availability);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tutor_availability_index');
    }
}
