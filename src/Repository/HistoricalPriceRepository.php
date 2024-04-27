<?php

namespace App\Repository;

use App\Entity\HistoricalPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoricalPrice>
 *
 * @method HistoricalPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoricalPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoricalPrice[]    findAll()
 * @method HistoricalPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoricalPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoricalPrice::class);
    }

//    /**
//     * @return HistoricalPrice[] Returns an array of HistoricalPrice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HistoricalPrice
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
