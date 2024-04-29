<?php

namespace App\Repository;

use App\Entity\ProductSalePointInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductSalePointInventory>
 *
 * @method ProductSalePointInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSalePointInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSalePointInventory[]    findAll()
 * @method ProductSalePointInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSalePointInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSalePointInventory::class);
    }

//    /**
//     * @return ProductSalePointInventory[] Returns an array of ProductSalePointInventory objects
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

//    public function findOneBySomeField($value): ?ProductSalePointInventory
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
