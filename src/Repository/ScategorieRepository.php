<?php

namespace App\Repository;

use App\Entity\Scategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Scategorie>
 */
class ScategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scategorie::class);
    }

    /**
     * @return Scategorie[] Returns an array of Scategorie objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.titre = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // Ajoute d'autres méthodes selon tes besoins
}
