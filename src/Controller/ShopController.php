<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     */
    public function index(PanierRepository $panierRep)
    {
        // Initialize panier data for non-authenticated users
        $existingArticles = [];
        $panierItemsCount = 0;
        
        // Check if the user is authenticated
        if ($this->getUser()) {
            $id_user = $this->getUser()->getId();
            $panierItems = $panierRep->findBy(['idClient' => $id_user]);
            $panierItemsCount = count($panierItems);

            $em = $this->getDoctrine()->getManager()->getRepository(Produit::class);
            $repository = $this->getDoctrine()->getRepository(Produit::class)->findAll();

            foreach ($panierItems as $panierItem) {
                // Check if $panierItem has an associated Produits object
                if ($panierItem->getIdProduit() !== null) {
                    $articleId = $panierItem->getIdProduit()->getId();

                    // Check if the article ID exists in the list of articles
                    foreach ($repository as $article) {
                        if ($article->getId() === $articleId) {
                            // Add the existing article to the list of existing articles
                            $existingArticles[] = $article->getId();
                            break;
                        }
                    }
                } else {
                    // Handle the case where $panierItem doesn't have an associated Produits
                    // For example, you can log an error message or skip this panier item
                }
            }
        }

        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();

        return $this->render('shop.html.twig', [
            'produits' => $produits,
            'existingArticles' => $existingArticles,
            'panierItemsCount' => $panierItemsCount,
        ]);
    }
}
