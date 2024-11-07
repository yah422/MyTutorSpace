<?php

namespace App\Controller;

use App\Repository\LeconRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, LeconRepository $leconRepository, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q');
        $results = [];

        if ($query) {
            // Recherche dans les leçons
            $lecons = $leconRepository->createQueryBuilder('l')
                ->where('l.titre LIKE :query')
                ->orWhere('l.description LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();

            // Recherche dans les tuteurs
            $tuteurs = $userRepository->createQueryBuilder('u')
                ->where('u.prenom LIKE :query')
                ->orWhere('u.nom LIKE :query')
                ->andWhere('u.roles LIKE :role')
                ->setParameter('query', '%' . $query . '%')
                ->setParameter('role', '%ROLE_TUTEUR%')
                ->getQuery()
                ->getResult();

            $results = [
                'lecons' => $lecons,
                'tuteurs' => $tuteurs
            ];
        }

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'results' => $results
        ]);
    }
}