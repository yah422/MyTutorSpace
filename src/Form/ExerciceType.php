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
            ->add('titre', TextType::class, [
                'label_attr' => ['class' => 'text-gray-900'], // Applique le style au label
            ])
            ->add('description', TextType::class, [
                'label_attr' => ['class' => 'text-gray-900'], // Applique le style au label
            ])
            ->add('dateCreation', null, [
                'widget' => 'single_text',
                'label_attr' => ['class' => 'text-gray-900'], // Applique le style au label
            ])
            ->add('lecon', EntityType::class, [
                'class' => Lecon::class,
                'label' => 'Leçon',
                'label_attr' => ['class' => 'text-gray-900'], // Applique le style au label
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'label' => 'Type',
                'label_attr' => ['class' => 'text-gray-900'], // Applique le style au label
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercice::class,
        ]);
    }
}
