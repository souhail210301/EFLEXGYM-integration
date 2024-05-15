<?php

namespace App\Repository;

use App\Entity\BilanFinancier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BilanFinancierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BilanFinancier::class);
    }

    public function getExistingRecordsCount(): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMaxId(): int
{
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        'SELECT MAX(b.id) AS maxId FROM App\Entity\BilanFinancier b'
    );
    $result = $query->getSingleResult();

    return $result['maxId'] ?? 0;
}
public function findOneByMonth(int $month, int $year): ?BilanFinancier
{
    return $this->createQueryBuilder('b')
        ->andWhere('MONTH(b.dateDebut) = :month')
        ->andWhere('YEAR(b.dateDebut) = :year')
        ->setParameter('month', $month)
        ->setParameter('year', $year)
        ->getQuery()
        ->getOneOrNullResult();
}



    
}
