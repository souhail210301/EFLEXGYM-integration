<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlanningRepository;
use App\Entity\Planning;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;



class CalendarplanningController extends AbstractController
{
    #[Route('/calendarplanning', name: 'app_calendarplanning', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository, UserRepository $userRepository): Response
    {
        // Assuming you have a default user ID or some logic to determine the user ID
        $userId = 23; // Replace '1' with the actual user ID
    
        // Fetch the user entity from the repository using the user ID
        $user = $userRepository->find($userId);
        $plannings = $planningRepository->findAll();
        
        return $this->render('calendarplanning/index.html.twig', [
            'controller_name' => 'CalendarplanningController',
            'plannings' => $plannings,
            'userId' => $userId, // Pass the 'userId' variable to the Twig template
        ]);
    }

}
