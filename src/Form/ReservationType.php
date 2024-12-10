<?php

namespace App\Form;

use App\Entity\Lecon;
use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lecons', EntityType::class, [
                'class' => Lecon::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->where('l.matiere IS NOT NULL');
                },
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => "Séléctionnez une date",
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'data flatpickr',
                    'placeholder' => "Cliquez ici pour séléctionner une date",
                ],
                'constraints'=>[
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'Veuillez séléctionner une date dans le présent !',
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                    new Length(['min' => 2, 'max' => 50]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom ne peut pas être vide.']),
                    new Length(['min' => 2, 'max' => 50]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez fournir un email.']),
                    new Email(['message' => 'Email non valide.']),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'data',
                    'placeholder' => 'Votre message',
                    'autocomplete' => 'off'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le message ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            // Honeypot field
            ->add('honeypot', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'style' => 'display:none',
                    'autocomplete' => 'off',
                    'tabindex' => '-1'
                ],
                'label' => false,
            ])
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true, // Indique que le champ est obligatoire
                'label' => 'Date de fin',
                'attr' => [
                    'class' => 'flatpickr', // Utilisation de Flatpickr si nécessaire
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'appointment'
        ]);
    }
}