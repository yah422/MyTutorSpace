<?php

namespace App\Controller;

use App\Entity\Lecon;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Form\LeconType;
use App\Repository\UserRepository;
use App\Repository\LeconRepository;
use App\Repository\MatiereRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class LeconController extends AbstractController
{
    #[Route('/lecon', name: 'app_lecon')]
    public function index(Request $request, LeconRepository $leconRepository, MatiereRepository $matiereRepository, EntityManagerInterface $entityManager): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $niveauRepository = $entityManager->getRepository(Niveau::class);
        $niveaux = $niveauRepository->findAll();
        
        $matiereId = $request->query->get('matiere');
        $niveauId = $request->query->get('niveau');
        
        $selectedMatiere = null;
        $selectedNiveau = null;
        
        if ($matiereId) {
            $selectedMatiere = $matiereRepository->find($matiereId);
        }
        
        if ($niveauId) {
            $selectedNiveau = $niveauRepository->find($niveauId);
        }
        
        $lecons = $leconRepository->findLeconsByFilters($selectedMatiere, $selectedNiveau);

        return $this->render('lecon/index.html.twig', [
            'lecons' => $lecons,
            'matieres' => $matieres,
            'niveaux' => $niveaux,
            'selectedMatiere' => $selectedMatiere,
            'selectedNiveau' => $selectedNiveau
        ]);
    }

    #[Route('/lecon/ajouter', name: 'add_lecon')]
    public function add(MatiereRepository $matiereRepository, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN') && !$security->isGranted('ROLE_TUTEUR')) {
            return $this->render('user/errorPage.html.twig');     
        }

        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $lecon = new Lecon();
        
        $lecon->setDateCreation(new \DateTime());

        $form = $this->createForm(LeconType::class, $lecon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdfFile = $form->get('pdfFile')->getData();

            if ($pdfFile) {
                $newFilename = uniqid().'.'.$pdfFile->guessExtension();

                try {
                    $pdfFile->move(
                        $this->getParameter('pdf_directory'),
                        $newFilename
                    );
                    $lecon->setPdfPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier PDF');
                    return $this->redirectToRoute('add_lecon');
                }
            }

            $entityManager->persist($lecon);
            $entityManager->flush();

            $this->addFlash('success', 'Leçon ajoutée avec succès !');
            return $this->redirectToRoute('app_lecon');
        }

        return $this->render('lecon/add.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
        ]);
    }

    #[Route('/lecon/edit/{id}', name: 'edit_lecon')]
    public function edit(UserRepository $userRepository, Lecon $lecon, MatiereRepository $matiereRepository, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN') && !$security->isGranted('ROLE_TUTEUR')) {
            return $this->render('user/errorPage.html.twig');     
        }
        $user = $lecon->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        
        $form = $this->createForm(LeconType::class, $lecon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdfFile = $form->get('pdfFile')->getData();

            if ($pdfFile) {
                $newFilename = uniqid().'.'.$pdfFile->guessExtension();

                try {
                    $pdfFile->move(
                        $this->getParameter('pdf_directory'),
                        $newFilename
                    );
                    $lecon->setPdfPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier PDF');
                    return $this->redirectToRoute('edit_lecon', ['id' => $lecon->getId()]);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Leçon modifiée avec succès !');
            return $this->redirectToRoute('show_lecon', ['id' => $lecon->getId()]);
        }

        return $this->render('lecon/edit.html.twig', [
            'form' => $form->createView(),
            'matieres' => $matieres,
            'lecon' => $lecon,
        ]);
    }

    #[Route('/lecon/supprimer/{id}', name: 'delete_lecon', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, CsrfTokenManagerInterface $csrfTokenManager, Lecon $lecon, EntityManagerInterface $entityManager): Response
    {
        if (!$lecon) {
            throw $this->createNotFoundException('Aucune leçon trouvée pour cet identifiant.');
        }

        if ($this->isCsrfTokenValid('delete_lecon', $request->request->get('_token'))) {
            $entityManager->remove($lecon);
            $entityManager->flush();

            $this->addFlash('success', 'Leçon supprimée avec succès !');
            return $this->redirectToRoute('app_lecon');
        }

        throw $this->createAccessDeniedException('Token CSRF invalide.');
    }

    #[Route('/lecon/matiere/{id}', name: 'lecons_par_matiere')]
    public function leconsParMatiere(Matiere $matiere, MatiereRepository $matiereRepository, EntityManagerInterface $entityManager): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $lecons = $entityManager->getRepository(Lecon::class)->findBy(['matiere' => $matiere]);

        return $this->render('lecon/show.html.twig', [
            'lecons' => $lecons,
            'matieres' => $matieres,
            'matiere' => $matiere,
        ]);
    }

    #[Route('/lecon/{id}', name: 'show_lecon')]
    public function show(LeconRepository $leconRepository,Lecon $lecon, MatiereRepository $matiereRepository, ExerciceRepository $exerciceRepository): Response
    {
        $matieres = $matiereRepository->findBy([], ["nom" => "ASC"]);
        $exercices = $exerciceRepository->findBy([], ["titre" => "ASC"]);
        $matiere = $lecon->getMatiere();

        $exercice = $leconRepository->findExercicesByLecon($lecon);

        return $this->render('lecon/detail.html.twig', [
            'lecon' => $lecon,
            'matieres' => $matieres,
            'exercice' => $exercice,
            'exercices' => $exercices,
            'matiere' => $matiere,
        ]);
    }
}
