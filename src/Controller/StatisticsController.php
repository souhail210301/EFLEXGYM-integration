<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\StatisticsService;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/statistics", name="statistics")
     */
    public function index(StatisticsService $statisticsService): Response
    {
        // Get category statistics from the service
        $categoryStatistics = $statisticsService->getCategoryStatistics();

        // Render the statistics in a template
        return $this->render('statt.html.twig', [
            'categoryStatistics' => $categoryStatistics,
        ]);
    }
}