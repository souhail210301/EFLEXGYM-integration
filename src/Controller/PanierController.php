<?php

namespace App\Controller;

use App\Service\panierService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(panierService $panierService, UserRepository $userRep, Request $request): Response
    {
        // Check if the user is authenticated
        $user = $this->getUser();
        
        // If not authenticated, redirect to login page
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Fetch panier items for the current user
        $panierData = $panierService->getCartItems($user->getId());
        $panierItemsCount = count($panierData);

        // Calculate total price of items in the panier
        $totalPrice = array_reduce($panierData, function ($total, $product) {
            return $total + $product->getIdProduit()->getPrix();
        }, 0);

        // Render the panier.html.twig template with necessary data
        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
            'panierData' => $panierData,
            'totalPrice' => $totalPrice,
            'panierItemsCount' => $panierItemsCount,
        ]);
    }

    #[Route('/addToPanier/{idp}', name: 'app_addToPanier')]
    public function addToPanier(Request $request, $idp, panierService $panierService, UserRepository $userRep, ProduitRepository $pr): Response
    {
        // Check if the user is authenticated
        $user = $this->getUser();
        
        // If not authenticated, redirect to login page
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $panierService->addToCart($user->getId(), $idp, $userRep, $pr);

        // Add flash message
        $this->addFlash('command_ajoute', 'Article ajouté au panier');

        return $this->redirectToRoute('produits');
    }

    #[Route('/removeFromPanier/{idp}', name: 'app_removeFromPanier')]
    public function removeFromPanier($idp, panierService $panierService, PanierRepository $panierRep): Response
    {
        // Check if the user is authenticated
        $user = $this->getUser();
        
        // If not authenticated, redirect to login page
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Remove the selected article from the user's panier
        $panierService->removeFromCart($idp, $panierRep);

        // Redirect back to the panier page
        return $this->redirectToRoute('app_panier');
    }
}
