<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use App\Form\ProfileType;


#[Route('/profile')]
class UserController1 extends AbstractController
{




    private $passwordHasher;
    private $entityManager;

    private $security;
    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }


    #[Route('/{userId}', name: 'profile_user_show', methods: ['GET'])]
    public function show1($userId): Response
    {

        $httpClient = new GuzzleClient();

        // Fetch weather data from OpenWeatherMap API
        $city = 'Tunis'; // Replace with the city you want to get the weather for
        $apiKey = 'e7e933b8ee14963f05103ef05c85201c';
        $response = $httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric', // Use 'metric' for Celsius, 'imperial' for Fahrenheit
            ],
        ]);
        $weatherData = json_decode($response->getBody()->getContents(), true);

        // Fetch user information based on the user ID
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        // Create Guzzle HTTP client


        // Render the profile page with user information and weather data
        return $this->render('profile.html.twig', [
            'user' => $user,
            'weatherData' => $weatherData,

        ]);
    }

    #[Route('/{userId}/edit', name: 'user_edit', methods: ['GET', 'POST'])] // Defines a route for editing user profile
    public function edit2(Request $request, int $userId, EntityManagerInterface $entityManager): Response
    {
        // Fetch the user from the repository based on the user ID
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a form for editing user profile using UserType form class
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request); // Handle the form submission

        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photoFile')->getData();

            // Check if a new photo is uploaded
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension(); // Generate a unique filename

                // Move the uploaded file to the specified directory
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'), // Define this parameter in services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error...
                }

                $user->setPhoto($newFilename); // Set the new photo filename in the user entity
            }

            // Hash the password using the injected passwordHasher
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->flush(); // Save the changes to the database

            // Redirect to the user profile page after editing
            return $this->redirectToRoute('profile_user_show', ['userId' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        // Render the edit profile form
        return $this->renderForm('editprofile.html.twig', [
            'user' => $user, // Pass the user entity to the view
            'form' => $form, // Pass the form to the view
        ]);
    }

        

}
