<?php

namespace App\Form;
use App\Entity\Evenement;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventName', TextType::class, [
                'label' => 'Event Name',
                'attr' => ['placeholder' => 'Enter the event name'],
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Select start date'],
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'End Date',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Select end date'],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration (in minutes)',
                'attr' => ['placeholder' => 'Enter duration'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Event Image',
                'required' => false,
                'mapped' => false, // Do not map this field to the entity property
            ])
            ->add('place', TextType::class, [
                'label' => 'Event Place',
                'attr' => ['placeholder' => 'Enter the event place'],
            ])
            ->add('type')
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
