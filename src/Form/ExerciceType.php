<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Lecon;
use App\Entity\Exercice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextType::class)
            ->add('dateCreation', null, [
                'widget' => 'single_text',
            ])
            ->add('lecon', EntityType::class, [
                'class' => Lecon::class,
                'label' => 'titre',
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'label' => 'nom',
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
