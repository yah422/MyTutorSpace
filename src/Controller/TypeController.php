<?php

namespace App\Controller;

use App\Repository\TypeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeController extends AbstractController
{
    #[Route('/type', name: 'app_type')]
    public function index(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();
    
        return $this->render('type/index.html.twig', [
            'types' => $types,
        ]);
    }
    
}
