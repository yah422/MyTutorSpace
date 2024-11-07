<?php

namespace App\Service;

use App\Form\SearchType;
use App\Model\SearchData;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

// class SearchService
// {
//     private $formFactory;

//     // Injection du FormFactory pour créer le formulaire
//     public function __construct(FormFactoryInterface $formFactory)
//     {
//         $this->formFactory = $formFactory;
//     }

//     // Méthode pour créer le formulaire de recherche
//     public function createSearchForm(Request $request)
//     {
//         // Créer un nouvel objet de données de recherche
//         $searchData = new SearchData();
        
//         // Créer le formulaire à partir de la classe SearchType
//         $form = $this->formFactory->create(SearchType::class, $searchData);
        
//         // Gérer la soumission du formulaire
//         $form->handleRequest($request);

//         // Retourner le formulaire et les données de recherche
//         return [$form, $searchData];
//     }
// }
class SearchService
{
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        // Injection du FormFactory pour créer le formulaire
        $this->formFactory = $formFactory;
    }

    public function createSearchForm(Request $request): \Symfony\Component\Form\FormInterface
    {
        $searchData = new SearchData();
        $form = $this->formFactory->create(SearchType::class, $searchData);
        $form->handleRequest($request);

        return $form;
    }
}
