<?php

namespace App\Controller\Admin;

use App\Entity\Lien;
use App\Entity\Type;
use App\Entity\Lecon;
use App\Entity\Niveau;
use App\Entity\Contact;
use App\Entity\Matiere;
use App\Entity\Message;
use App\Entity\Exercice;
use App\Entity\Ressource;
use App\Entity\Forum\Post;
use App\Entity\Forum\Topic;
use App\Entity\Forum\Category;
use App\Entity\TutoringBooking;
use App\Entity\SauvegardeProfil;
use App\Entity\TutorAvailability;
use App\Controller\Admin\LeconCrudController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(LeconCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyTutorSpace');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-home');
        yield MenuItem::linkToCrud('Lecon', 'fas fa-book', Lecon::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-folder', Category::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-envelope', Contact::class);
        yield MenuItem::linkToCrud('Exercice', 'fas fa-dumbbell', Exercice::class);
        yield MenuItem::linkToCrud('Lien', 'fas fa-link', Lien::class);
        yield MenuItem::linkToCrud('Matiere', 'fas fa-graduation-cap', Matiere::class);
        yield MenuItem::linkToCrud('Message', 'fas fa-comments', Message::class);
        yield MenuItem::linkToCrud('Niveau', 'fas fa-layer-group', Niveau::class);
        yield MenuItem::linkToCrud('Post', 'fas fa-newspaper', Post::class);
        yield MenuItem::linkToCrud('Ressource', 'fas fa-file-alt', Ressource::class);
        yield MenuItem::linkToCrud('SauvegardeProfil', 'fas fa-save', SauvegardeProfil::class);
        yield MenuItem::linkToCrud('Topic', 'fas fa-comment-dots', Topic::class);
        yield MenuItem::linkToCrud('TutorAvailability', 'fas fa-calendar-check', TutorAvailability::class);
        yield MenuItem::linkToCrud('TutoringBooking', 'fas fa-calendar-alt', TutoringBooking::class);
        yield MenuItem::linkToCrud('Type', 'fas fa-tags', Type::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
    }

}