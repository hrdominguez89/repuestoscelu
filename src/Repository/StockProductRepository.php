<?php

namespace App\Repository;

use App\Entity\StockProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockProduct>
 *
 * @method StockProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockProduct[]    findAll()
 * @method StockProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockProduct::class);
    }

//    /**
//     * @return StockProduct[] Returns an array of StockProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StockProduct
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
