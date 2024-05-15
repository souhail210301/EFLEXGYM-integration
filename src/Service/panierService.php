<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\PanierRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Panier;
use App\Repository\ProduitRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UserRepository;

class panierService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addToCart($userId, $prodId, UserRepository $userRep, ProduitRepository $pr)
    {
        $user = $userRep->find($userId);
        $article = $pr->find($prodId);

        $panier = new Panier();
        $panier->setIdClient($user);
        $panier->setIdProduit($article);
        // $panier->setDateAjout(new \DateTime());

        $this->entityManager->persist($panier);
        $this->entityManager->flush();
    }

    public function getCartItems($userId)
    {
        $panier = $this->entityManager->getRepository(Panier::class)->findBy([
            'idClient' => $userId
        ]);

        return $panier;
    }

    public function removeFromCart($panierId, PanierRepository $panierRep)
    {
        $panier = $panierRep->find($panierId);

        if (!$panier) {
            throw new \Exception('panier item not found');
        }

        $this->entityManager->remove($panier);
        $this->entityManager->flush();
    }

    public function emptyCart($userId)
    {
        $panier = $this->entityManager->getRepository(Panier::class)->findBy([
            'idClient' => $userId
        ]);

        foreach ($panier as $item) {
            $this->entityManager->remove($item);
        }

        $this->entityManager->flush();
    }
}