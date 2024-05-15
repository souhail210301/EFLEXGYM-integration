<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\BilanFinancier;
use App\Enum\Role;
use App\Enum\RoleUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => false, // Render as checkboxes
                'required' => true
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Adherent' => 'Adherent',
                    'Coach' => 'Coach',
                    // Choices array
                ],
                'multiple' => false, // Ensure it accepts only single values
            ])

            ->add('password', PasswordType::class)    
            // Add form fields for the new properties
                ->add('name')
                ->add('lastName')
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                // You can customize the date format as needed
                'format' => 'yyyy-MM-dd',
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'placeholder' => 'Select gender',
                'required' => true,
               
            ])
            
            ->add('photoFile', FileType::class, [
                'label' => 'Profile Photo',
                'required' => false,
                'mapped' => false, // This is important for handling file uploads properly
            ])
        ->add('salaire')
            ->add('idBilanFinancier', ChoiceType::class, [
                'choices' => $options['bilan_financier_choices'],
                'choice_label' => 'id', // or any other property you want to display
                // Other options as needed
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'bilan_financier_choices' => [], // Default empty array for choices
        ]);
    }
}