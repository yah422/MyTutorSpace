<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil (optionnel)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG ou PNG)',
                    ])
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email   ',
                'attr' => [
                    'class' => 'cutomLabel'
                ]
            ])
            ->add('nom', null, [
                'label' => 'Nom   ',
                'attr' => [
                    'class' => 'cutomLabel'
                ]
            ])
            ->add('prenom', null, [
                'label' => 'Prénom   ',
                'attr' => [
                    'class' => 'cutomLabel'
                ]
            ])
            ->add('aboutMe', null, [
                'label' => 'About Me   ',
                'attr' => [
                    'class' => 'cutomLabel'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe   ',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe est obligatoire.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
