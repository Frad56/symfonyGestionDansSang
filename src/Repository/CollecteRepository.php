<?php

namespace App\Repository;

use App\Entity\Collecte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collecte>
 */
class CollecteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collecte::class);
    }


    public function findByFilters($lieu, $dateDebut)
    {
    $qb = $this->createQueryBuilder('c')
        ->join('c.lieu', 'l')
        ->addSelect('l');

    if ($lieu) {
        $qb->andWhere('l.ville = :ville')
           ->setParameter('ville', $lieu->getVille());
    }

    if ($dateDebut) {
        $qb->andWhere('c.dateDebut >= :dateDebut')
           ->setParameter('dateDebut', $dateDebut);
    }

    return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return Collecte[] Returns an array of Collecte objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Collecte
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
