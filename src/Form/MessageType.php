<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout d'un champ de texte pour saisir le contenu du message
        $builder
            ->add('messageContent', TextareaType::class, [
                'label' => ' ',
                'attr' => [
                    'class' => 'block w-full px-4 py-2 border rounded-md',
                ],
            ]);
        // Vérifie si un destinataire est fourni via les options
        if (isset($options['receiver'])) {
            // Si un destinataire est défini, ajoute un champ prérempli pour le destinataire
            $builder
                ->add('receiver', EntityType::class, [
                    'class' => User::class, // Classe associée au champ
                    'choice_label' => function (User $user) {
                        $roleNames = [
                            'ROLE_TUTEUR' => 'Tuteur',
                            'ROLE_ELEVE' => 'Élève',
                            'ROLE_PARENT' => 'Parent',
                            'ROLE_USER' => 'Utilisateur',
                        ];

                        // Récupérer le premier rôle de l'utilisateur
                        $roles = $user->getRoles();
                        $role = $roles[0] ?? 'Utilisateur'; // Par défaut "Utilisateur" si aucun rôle trouvé
        
                        return $user->getNom() . ' ' . $user->getPrenom() .  ' (' . ($roleNames[$role] ?? $role) . ')';
                    },
                    'query_builder' => function (\App\Repository\UserRepository $userRepository) {
                        return $userRepository->createQueryBuilder('u')
                            ->where("u.roles NOT LIKE :adminRole")
                            ->setParameter('adminRole', '%ROLE_ADMIN%'); // Exclut l'admin
                    },
                    'data' => $options['receiver'], // Valeur par défaut : le destinataire passé en option

                    // Cache le champ et son label pour qu'ils ne soient pas visibles dans le formulaire
                    'attr' => [
                        'class' => 'hidden', // Cache visuellement le champ
                    ],
                    'label_attr' => [
                        'class' => 'hidden', // Cache visuellement le label
                    ],
                ]);
        } else {
            // Si aucun destinataire par défaut n'est défini, permet de choisir un utilisateur
            $builder
                ->add('receiver', EntityType::class, [
                    'label' => 'Destinataire',
                    'class' => User::class,
                    'choice_label' => function (User $user) {
                        $roleNames = [
                            'ROLE_TUTEUR' => 'Tuteur',
                            'ROLE_ELEVE' => 'Élève',
                            'ROLE_PARENT' => 'Parent',
                            'ROLE_USER' => 'Utilisateur',
                        ];

                        // Récupérer le premier rôle de l'utilisateur
                        $roles = $user->getRoles();
                        $role = $roles[0] ?? 'Utilisateur'; // Par défaut "Utilisateur" si aucun rôle trouvé
        
                        return $user->getNom() . ' ' .  $user->getPrenom() .  ' (' . ($roleNames[$role] ?? $role) . ')';
                    }, // Permet de sélectionner un utilisateur via le prenom
                    // Cache le champ et son label pour qu'ils ne soient pas visibles dans le formulaire
                    'query_builder' => function (\App\Repository\UserRepository $userRepository) {
                        return $userRepository->createQueryBuilder('u')
                            ->where("u.roles NOT LIKE :adminRole")
                            ->setParameter('adminRole', '%ROLE_ADMIN%'); // Exclut l'admin
                    },
                    'attr' => [
                        'class' => 'hidden', // Cache visuellement le champ
                    ],
                    'label_attr' => [
                        'class' => 'hidden', // Cache visuellement le label
                    ],
                ]);
        }
        // Ajout d'un bouton pour soumettre le formulaire
        // $builder->add('Envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure les options par défaut du formulaire
        $resolver->setDefaults([
            'data_class' => Message::class, // Associe ce formulaire à l'entité Message
            'receiver' => null, // Valeur par défaut pour le destinataire si aucun n'est fourni
        ]);
    }
}
