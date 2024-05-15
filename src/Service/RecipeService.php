<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class RecipeService
{
  private $appID;
  private $apiKey;
  private $httpClient;

  public function __construct()
  {
    $this->apiKey = $_ENV['RECIPE_APP_KEY'];
    $this->appID = $_ENV['RECIPE_APP_ID'];
    $this->httpClient = HttpClient::create();
  }

  public function searchRecipes(string $query): array
  {
    $response = $this->httpClient->request('GET', 'https://api.edamam.com/api/recipes/v2', [
      'query' => [
        'type' => 'public',
        'q' => $query, //instruction sushi
        'app_id' => $this->appID,
        'app_key' => $this->apiKey,
      ],
    ]);

    return $response->toArray();
  }
}
