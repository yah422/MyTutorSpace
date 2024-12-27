<?php

namespace App\Form;

use App\Entity\Seance;
use App\Entity\User;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eleve', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getPrenom() . ' ' . $user->getNom();
                }
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nom'
            ])
            ->add('date', DateTimeType::class)
            ->add('compte_rendu', TextareaType::class)
            ->add('objectifs', TextareaType::class)
            ->add('travail_maison', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
