<?php

namespace App\Repository;

use App\Entity\ProgrammePersonnalise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProgrammePersonnalise|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgrammePersonnalise|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgrammePersonnalise[]    findAll()
 * @method ProgrammePersonnalise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammePersonnaliseRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ProgrammePersonnalise::class);
  }

  public function findAllManagedEntities(): array
  {
    $entityManager = $this->getEntityManager();
    $entities = $this->createQueryBuilder('p')
      ->getQuery()
      ->getResult();

    // Ensure that each entity is managed
    foreach ($entities as $entity) {
      $entityManager->persist($entity);
    }

    return $entities;
  }
}
