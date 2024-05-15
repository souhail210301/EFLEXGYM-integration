<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a type for the category.'
                    ]),
                    new Choice([
                        'choices' => ['vestimentaire', 'alimentaire'],
                        'message' => 'Type must be either "vestimentaire" or "alimentaire".'
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description for the category.'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Description must be at least {{ limit }} characters long.'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
            // Enable client-side validation
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'categorie'; // Form name prefix
    }

    // Add a method to generate JavaScript code for client-side validation
    public function generateJavaScriptValidation(): string
    {
        $validationCode = <<<JS
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('categorie').addEventListener('submit', function (event) {
        var type = document.getElementById('categorie_type').value;
        var description = document.getElementById('categorie_description').value;

        clearErrorMessages();

        // Validate type
        if (type.trim() === '' || !['vestimentaire', 'alimentaire'].includes(type.trim())) {
            appendErrorMessage('categorie_type', 'Type must be either "vestimentaire" or "alimentaire".');
        }

        // Validate description
        if (description.trim().length < 10) {
            appendErrorMessage('categorie_description', 'Please enter a description with at least 10 characters.');
        }

        // Check if there are any errors
        if (document.querySelectorAll('.error-message').length > 0) {
            event.preventDefault(); // Prevent form submission
        }
    });
});

function clearErrorMessages() {
    var errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(function(errorMessage) {
        errorMessage.remove();
    });
}

function appendErrorMessage(fieldId, message) {
    var field = document.getElementById(fieldId);
    var errorMessage = document.createElement('div');
    errorMessage.classList.add('error-message');
    errorMessage.textContent = message;
    field.parentNode.appendChild(errorMessage);
}
JS;

        return $validationCode;
    }
}
