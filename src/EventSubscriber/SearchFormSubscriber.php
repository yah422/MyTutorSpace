<?php

namespace App\EventSubscriber;

use App\Form\SearchType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class SearchFormSubscriber implements EventSubscriberInterface
{
    private $formFactory;
    private $twig;
    private $requestStack;

    public function __construct(FormFactoryInterface $formFactory, Environment $twig, RequestStack $requestStack)
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    public function onKernelController(ControllerEvent $event)
    {
        // Récupération de la requête actuelle
        $request = $this->requestStack->getCurrentRequest();

        // Vérifie que la requête est bien disponible
        if (!$request) {
            return;
        }

        // Création et traitement du formulaire de recherche
        $searchForm = $this->formFactory->create(SearchType::class);
        $searchForm->handleRequest($request);

        // Ajoute les formulaires en tant que variables globales Twig
        $this->twig->addGlobal('searchForm', $searchForm->createView());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}