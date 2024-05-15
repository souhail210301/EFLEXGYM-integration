<?php
namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\BilanFinancier;


class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    // Exemple de méthode de requête personnalisée pour trouver tous les abonnements actifs
    public function findActiveAbonnements()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.dateFin > :today')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getResult();
    }
    public function calculateRevenusAbonnements(BilanFinancier $bilanFinancier): ?float
{
    $queryBuilder = $this->createQueryBuilder('a')
        ->select('SUM(a.prix)')
        ->andWhere('a.idBilanFinancier = :bilanFinancier')
        ->setParameter('bilanFinancier', $bilanFinancier);

    $result = $queryBuilder->getQuery()->getSingleScalarResult();

    return $result ? (float) $result : null;
}
public function findByType($type)
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.type = :val')
        ->setParameter('val', $type)
        ->orderBy('a.id', 'ASC')
        ->getQuery()
        ->getResult()
    ;
}
public function findByNomAdherent($nom): array
{
    return $this->createQueryBuilder('a')
        ->join('a.id', 'u')
        ->andWhere('u.name LIKE :name')
        ->setParameter('name', '%'.$nom.'%')
        ->getQuery()
        ->getResult();
}



}
