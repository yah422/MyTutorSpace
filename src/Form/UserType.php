<?php

namespace App\Form;

use App\Entity\Lecon;
use App\Entity\Matiere;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour l'email avec type spécifique EmailType
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            // Champ pour le nom de l'utilisateur
            ->add('nom', null, [
                'label' => 'Nom',
            ])
            // Champ pour le prénom de l'utilisateur
            ->add('prenom', null, [
                'label' => 'Prénom',
            ])
            // // Champ pour la vérification du compte
            // ->add('isVerified', null, [
            //     'label' => 'Compte vérifié',
            //     'required' => false,
            // ])
            // // Champ pour les rôles en tant que choix multiple
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Utilisateur' => 'ROLE_USER',
            //         'Administrateur' => 'ROLE_ADMIN',
            //         'Parent' => 'ROLE_PARENT',
            //         'Élève' => 'ROLE_ELEVE',
            //         'Tuteur' => 'ROLE_TUTEUR',
            //     ],
            //     'expanded' => true,
            //     'multiple' => true,
            //     'label' => 'Rôles',
            // ])
            // Champ pour le mot de passe avec type PasswordType
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
            ]);
            // // Champ pour associer les matières liées à l'utilisateur
            // ->add('matieres', EntityType::class, [
            //     'class' => Matiere::class,
            //     'choice_label' => 'nom',  // On affiche le nom de la matière
            //     'multiple' => true,
            //     'expanded' => true,  // Pour afficher sous forme de cases à cocher
            //     'label' => 'Matières',
            // ])
            // // Champ pour associer les leçons liées à l'utilisateur
            // ->add('lecons', EntityType::class, [
            //     'class' => Lecon::class,
            //     'choice_label' => 'titre',  // On affiche le titre de la leçon
            //     'multiple' => true,
            //     'expanded' => true,  // Pour afficher sous forme de cases à cocher
            //     'label' => 'Leçons',
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définition de la classe de données par défaut pour le formulaire
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
