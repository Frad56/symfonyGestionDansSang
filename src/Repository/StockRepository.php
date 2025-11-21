<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stock>
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    /**
     * Returns stocks where the current level is below the alert level.
     *
     * @return Stock[]
     */
    public function findCriticalStock(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.niveauActuel < s.niveauAlerte')
            ->getQuery()
            ->getResult();
    }
}
