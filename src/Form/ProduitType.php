<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File; // Add this line for file validation

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name for the product.'
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([ // Add file validation constraints
                        'maxSize' => '1024k', // Adjust file size limit as needed
                        'mimeTypes' => [ // Adjust allowed MIME types as needed
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image.', // Error message for invalid file types
                    ]),
                ],
            ])
            ->add('prix', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a price for the product.'
                    ]),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'Please enter a valid price for the product.'
                    ]),
                ],
            ])
            ->add('quantite', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a quantity for the product.'
                    ]),
                    new Type([
                        'type' => 'integer',
                        'message' => 'Please enter a valid quantity for the product.'
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description for the product.'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Description must be at least {{ limit }} characters long.'
                    ]),
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'type',
                'placeholder' => 'Select a category',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a category for the product.'
                    ]),
                ],
            ])
            ->add('id_bilan_financier')
            ->add('id_admin');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            // Enable client-side validation
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'produit'; // Form name prefix
    }

    // Add a method to generate JavaScript code for client-side validation
    public function generateJavaScriptValidation(): string
    {
        $validationCode = <<<JS
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('produit').addEventListener('submit', function (event) {
            var nom = document.getElementById('produit_nom');
            var prix = document.getElementById('produit_prix');
            var quantite = document.getElementById('produit_quantite');
            var description = document.getElementById('produit_description');
            var categorie = document.getElementById('produit_categorie');
            var image = document.getElementById('produit_image'); // Add this line to get the image input
    
            clearErrorMessages();
    
            // Validate image
            if (image.files.length === 0) {
                appendErrorMessage(image, 'Please choose an image.');
            }
    
            // Validate nom, prix, quantite, description, categorie (existing validation logic)
    
            // Check if there are any errors
            if (document.querySelectorAll('.error-message').length > 0) {
                event.preventDefault(); // Prevent form submission
            }
        });
    });
    
    // Add clearErrorMessages and appendErrorMessage functions (existing validation functions)
    JS;
    
        return $validationCode;
    }
}
