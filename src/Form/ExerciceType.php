<?php

namespace App\Form;

use App\Entity\Exercice;
use App\Entity\Lecon;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('dateCreation', null, [
                'widget' => 'single_text',
            ])
            ->add('lecon', EntityType::class, [
                'class' => Lecon::class,
                'choice_label' => 'id',
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercice::class,
        ]);
    }
}
