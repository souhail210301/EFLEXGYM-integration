<?php

namespace App\Form;

use App\Entity\Exercice;
use App\Entity\ProgrammePersonnalise;
use App\Repository\ProgrammePersonnaliseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciceType extends AbstractType
{
    private $programmePersonnaliseRepository;

    public function __construct(ProgrammePersonnaliseRepository $programmePersonnaliseRepository)
    {
        $this->programmePersonnaliseRepository = $programmePersonnaliseRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('nbrdeset')
            ->add('groupemusculaire')
            ->add('nbrderepetitions')
            ->add('categorieexercice')
            ->add('typeequipement')
            ->add('programmes',  EntityType::class, [
                'class' => ProgrammePersonnalise::class,
                'choice_label' => 'objectif',
                'choices' => $this->programmePersonnaliseRepository->findAll(),
                'placeholder' => 'Choose a Program',
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercice::class,
        ]);
    }
}
