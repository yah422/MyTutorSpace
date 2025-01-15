<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\SauvegardeProfil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SauvegardeProfilController extends AbstractController
{
    #[Route('/sauvegarder-profil/{id}', name: 'app_save_profile', methods: ['POST'])]
    public function saveProfile(
        #[CurrentUser] User $user,
        User $tuteur,
        EntityManagerInterface $em
    ): RedirectResponse {
        // Vérifier si le profil est déjà sauvegardé
        $existingSauvegarde = $em->getRepository(SauvegardeProfil::class)
            ->findOneBy(['user' => $user, 'tuteur' => $tuteur]);

        if ($existingSauvegarde) {
            $this->addFlash('warning', 'Ce profil est déjà sauvegardé.');
            return $this->redirectToRoute('app_list_saved_profiles');
        }

        // Créer une nouvelle sauvegarde
        $sauvegarde = new SauvegardeProfil();
        $sauvegarde->setDateSauvegarde(new \DateTime());
        $sauvegarde->setUser($user);
        $sauvegarde->setTuteur($tuteur);

        $em->persist($sauvegarde);
        $em->flush();

        $this->addFlash('success', 'Votre profil a été sauvegardé.');
        return $this->redirectToRoute('app_list_saved_profiles');

    }

    #[Route('/liste-sauvegardes', name: 'app_list_saved_profiles')]
    public function listSavedProfiles(#[CurrentUser] User $user): Response
    {
        $sauvegardes = $user->getSauvegardeProfils();

        $tuteur = null;

        if (in_array('ROLE_TUTEUR', $user->getRoles(), true)) {
            $tuteur = $user; // L'utilisateur est lui-même le tuteur
        }

        return $this->render('sauvegarde_profil/index.html.twig', [
            'sauvegardes' => $sauvegardes,
            'user' => $user,
            'tuteur' => $tuteur,
        ]);
    }

    #[Route('/delete-profil/{id}', name: 'delete_profile', methods: ['POST'])]
    public function deleteSavedProfile(
        #[CurrentUser] User $user,
        SauvegardeProfil $sauvegarde,
        EntityManagerInterface $em
    ): RedirectResponse {
        if ($sauvegarde->getUser() !== $user) {
            $this->addFlash('error', 'Action non autorisée.');
            return $this->redirectToRoute('app_list_saved_profiles');
        }

        $em->remove($sauvegarde);
        $em->flush();

        $this->addFlash('success', 'Profil retiré des sauvegardes.');
        return $this->redirectToRoute('app_list_saved_profiles');
    }

    #[Route('/profil-est-sauvegardé/{id}', name: 'is_profile_saved', methods: ['GET'])]
    public function isProfileSaved(
        #[CurrentUser] User $user,
        User $tuteur,
        EntityManagerInterface $em
    ): Response {
        $sauvegarde = $em->getRepository(SauvegardeProfil::class)
            ->findOneBy(['user' => $user, 'tuteur' => $tuteur]);

        return $this->json(['saved' => $sauvegarde !== null]);
    }

}
