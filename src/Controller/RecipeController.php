<?php

namespace App\Controller;

use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipes', name: 'recipes')]
class RecipeController extends AbstractController
{
  #[Route('/', name: 'recipes_index', methods: ['GET'])]
  public function index(): Response
  {
    // Add any logic you want to execute when accessing /recipes/ with a GET request
    // For example, you can render a page displaying all recipes
    return $this->render('home/index.html.twig');
  }
  #[Route('/search', name: 'recipes_search_form_submit', methods: ['GET', 'POST'])]
  public function searchFormSubmit(Request $request): RedirectResponse
  {
    // Get the search query from the request
    $query = $request->request->get('query');

    // Redirect to the recipes_search route with the query parameter
    return $this->redirectToRoute('recipes_search', ['query' => $query]);
  }

  #[Route('/{query}', name: 'recipes_search', methods: ['GET'])]
  public function search(RecipeService $recipeService, string $query): Response
  {
    // Search for recipes
    $response = $recipeService->searchRecipes($query);

    // Extract recipe hits from the response
    $hits = $response['hits'] ?? [];

    // Extract and format recipe data
    $recipes = [];
    foreach ($hits as $hit) {
      $recipe = $hit['recipe'] ?? null;
      if ($recipe) {
        $recipes[] = [
          'label' => $recipe['label'] ?? '',
          'image' => $recipe['image'] ?? '',
          'source' => $recipe['source'] ?? '',
          'url' => $recipe['url'] ?? '',
          'yield' => $recipe['yield'] ?? '',
          'calories' => $recipe['calories'] ?? '',
          'dietLabels' => $recipe['dietLabels'] ?? [],
          'healthLabels' => $recipe['healthLabels'] ?? [],
          'cautions' => $recipe['cautions'] ?? [],
          'ingredients' => $recipe['ingredientLines'] ?? [],
          'instructions' => $recipe['instructions'] ?? [],
        ];
      }
    }

    return $this->render('suivi_progre/search.html.twig', [
      'recipes' => $recipes,
    ]);
  }
}
