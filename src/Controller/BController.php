<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BController extends AbstractController
{
    #[Route('/b', name: 'app_b')]
    public function index(): Response
    {
        return $this->render('b/index.html.twig', [
            'controller_name' => 'BController',
        ]);
    }
}
