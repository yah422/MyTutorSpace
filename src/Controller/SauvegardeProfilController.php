<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\SauvegardeProfil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SauvegardeProfilController extends AbstractController
{

    #[Route('/sauvegarder-profil/{id}', name: 'app_save_profile', methods: ['POST'])]    public function saveProfile(
        #[CurrentUser] User $user,
        User $tuteur,
        EntityManagerInterface $em
    ): JsonResponse {
        // Vérifier si le profil est déjà sauvegardé
        $existingSauvegarde = $em->getRepository(SauvegardeProfil::class)
            ->findOneBy(['user' => $user, 'tuteur' => $tuteur]);

        if ($existingSauvegarde) {
            return new JsonResponse(['message' => 'Ce profil est déjà sauvegardé.'], 400);
        }

        // Créer une nouvelle sauvegarde
        $sauvegarde = new SauvegardeProfil();
        $sauvegarde->setDateSauvegarde(new \DateTime());
        $sauvegarde->setUser($user);
        $sauvegarde->setTuteur($tuteur);

        $em->persist($sauvegarde);
        $em->flush();

        return new JsonResponse(['message' => 'Profil sauvegardé avec succès.'], 200);
    }

    #[Route('/liste-sauvegardes', name: 'app_list_saved_profiles')]
    public function listSavedProfiles(#[CurrentUser] User $user): Response
    {
        $sauvegardes = $user->getSauvegardeProfils();

        return $this->render('sauvegarde_profil/list.html.twig', [
            'sauvegardes' => $sauvegardes,
        ]);
    }

    #[Route('/delete-profil/{id}', name:'delete_profile')]
    public function deleteSavedProfile(
        #[CurrentUser] User $user,
        SauvegardeProfil $sauvegarde,
        EntityManagerInterface $em
    ): JsonResponse {
        if ($sauvegarde->getUser() !== $user) {
            return new JsonResponse(['message' => 'Action non autorisée.'], 403);
        }

        $em->remove($sauvegarde);
        $em->flush();

        return new JsonResponse(['message' => 'Profil retiré des sauvegardes.'], 200);
    }



}
