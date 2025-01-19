<?php
namespace App\Form;

use App\Entity\Progress;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProgressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dependent', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email', // Customize as needed
                'label' => 'Dependent (Child)',
            ])
            ->add('tutoringBooking', TextType::class, [
                'label' => 'Tutoring Booking',
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Comment',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Progress::class,
        ]);
    }
}
