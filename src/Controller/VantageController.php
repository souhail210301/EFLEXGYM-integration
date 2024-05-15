<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Psr\Log\LoggerInterface;

class VantageController extends AbstractController
{
    #[Route('/vantage', name: 'app_vantage')]
    public function index(LoggerInterface $logger): Response
    {
        // Create an HTTP client to make API requests
        $httpClient = HttpClient::create();

        // Set your Alpha Vantage API key and stock symbol
        $apiKey = 'YOUR_ALPHA_VANTAGE_API_KEY';
        $symbol = 'PTON'; // Example stock symbol (replace with your desired symbol)

        // Endpoint URL for Alpha Vantage API
        $endpoint = "https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol={$symbol}&interval=5min&apikey={$apiKey}";

        try {
            // Make a GET request to the Alpha Vantage API
            $response = $httpClient->request('GET', $endpoint);

            // Convert the API response to an associative array
            $data = $response->toArray();

            // Pass the retrieved data to the Twig template for rendering
            return $this->render('bilan_financier/stats.html.twig', [
                'data' => $data, // Pass the API response data to the Twig template
            ]);
        } catch (\Exception $e) {
            // Log any errors encountered during the API request
            $errorMessage = $e->getMessage();
            $logger->error('Failed to fetch data from Alpha Vantage API: ' . $errorMessage);

            // Optionally, render an error page or redirect with a flash message
            return $this->render('error.html.twig', [
                'message' => 'Failed to fetch data from Alpha Vantage API. Please try again later.',
            ]);
        }
    }
}
