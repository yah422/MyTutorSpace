<?php

namespace App\Controller;

use App\Form\SearchData;
use App\Form\SearchType;
use App\Entity\Matiere;
use App\Repository\MatiereRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PaginatorInterface $paginator,Matiere $matiere, MatiereRepository $matiereRepository): Response
    {
        // Page actuelle
        $searchData->page = $request->query->getInt('page', 1);

        // Si le formulaire est soumis et valide, on effectue une recherche
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repository->findBySearch($searchData); // Requête filtrée

            // Pagination des résultats de recherche
            $produits = $paginator->paginate(
                $query,
                $searchData->page,
                9 
            );

            return $this->render('produit/index.html.twig', [
                'form' => $form->createView(),
                'produits' => $produits,
            ]);
        }

        // Si aucune recherche, affiche tous les produits paginés
        $produits = $paginator->paginate(
            $repository->findAll(),
            $searchData->page,
            9 
        );

        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $q = $searchData->getQ();
        }
        $matieres = $matiereRepository->findBy([],["nom" => "ASC"]);

        return $this->render('home/index.html.twig', [
            'matieres' => $matieres,
            'matiere' => 'matiere',
        ]);
    }
}
