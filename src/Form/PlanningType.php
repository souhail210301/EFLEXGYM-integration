<?php

namespace App\Form;

use App\Entity\Planning;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\GreaterThan;


class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('salle')
            ->add('nbPlaceMax')
            ->add('date', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date ne peut pas être vide.',
                    ]),
                    new GreaterThan([
                        'value' => new \DateTime(), // Today's date
                        'message' => 'La date de début doit être future à aujourd\'hui.',
                    ]),
                ],
            ])
            ->add('heure')
            ->add('cour', EntityType::class, [
                'class' => 'App\Entity\Cours',
                'choice_label' => 'nom',
            ])
            ->add('coach', EntityType::class, [
                'class' => User::class,
                'label' => 'Coach',
                'choice_label' => 'nom',
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}
