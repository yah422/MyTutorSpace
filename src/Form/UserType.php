<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('photoFile', FileType::class, [
                'required' => false,
                'label' => 'Photo de profil',
                'mapped' => false, // n'est pas directement lié à la base de données
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email   ',
                'attr' => [ 
                    'class' => 'cutomLabel' 
                ]])
            ->add('nom', null, [
                'label' => 'Nom   ',
                'attr' => [ 
                    'class' => 'cutomLabel' 
                ]])
            ->add('prenom', null, [
                'label' => 'Prénom   ',
                'attr' => [ 
                    'class' => 'cutomLabel' 
                ]])
            ->add('aboutMe', null, [
                'label' => 'About Me   ',
                'attr' => [ 
                    'class' => 'cutomLabel' 
                ]])
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
