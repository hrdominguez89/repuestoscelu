<?php

namespace App\Repository;

use App\Entity\ProductsSalesPoints;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductsSalesPoints>
 *
 * @method ProductsSalesPoints|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsSalesPoints|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsSalesPoints[]    findAll()
 * @method ProductsSalesPoints[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsSalesPointsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsSalesPoints::class);
    }

//    /**
//     * @return ProductsSalesPoints[] Returns an array of ProductsSalesPoints objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductsSalesPoints
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
