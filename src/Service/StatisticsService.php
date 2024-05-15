<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatisticsService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCategoryStatistics()
    {
        // Retrieve data from the database
        $query = $this->entityManager->createQuery(
            'SELECT c.type as category, COUNT(p.id) as product_count
             FROM App\Entity\Produit p
             JOIN p.categorie c
             GROUP BY c.type'
        );

        $statistics = $query->getResult();

        // Process the data (if needed)
        // For example, calculate average price per category

        return $statistics;
    }
}