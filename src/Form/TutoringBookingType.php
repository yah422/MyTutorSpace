<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\TutoringBooking;
use App\Entity\Subject;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class TutoringBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('studentName', TextType::class, [
                'label' => 'Nom complet',
                'required' => true,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500',
                    'placeholder' => 'Votre nom complet'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nom']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('studentEmail', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500',
                    'placeholder' => 'votre.email@exemple.com'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre email']),
                    new Email(['message' => 'Veuillez entrer un email valide'])
                ]
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nom',
                'label' => 'Matière',
                'required' => true,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message (facultatif)',
                'required' => false,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500',
                    'rows' => 4,
                    'placeholder' => 'Décrivez vos besoins spécifiques...'
                ]
            ])
            ->add('preferredDate', DateType::class, [
                'label' => 'Date souhaitée',
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500'
                ],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être dans le futur'
                    ])
                ]
            ])
            ->add('preferredTimeSlot', ChoiceType::class, [
                'label' => 'Créneau horaire préféré',
                'choices' => [
                    'Matin (9h-12h)' => 'matinée',
                    'Après-midi (14h-17h)' => 'après-midi',
                    'Soir (18h-20h)' => 'soirée'
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'attr' => [
                    'class' => 'mt-1 space-y-2 text-black'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Réserver le cours',
                'attr' => [
                    'class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm text-black font-medium bg-[#70F9D9] hover:bg-[#FFD700] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TutoringBooking::class
        ]);
    }
}