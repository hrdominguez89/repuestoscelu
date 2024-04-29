<?php

namespace App\Repository;

use App\Entity\ProductAdminInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductAdminInventory>
 *
 * @method ProductAdminInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAdminInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAdminInventory[]    findAll()
 * @method ProductAdminInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAdminInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAdminInventory::class);
    }

//    /**
//     * @return ProductAdminInventory[] Returns an array of ProductAdminInventory objects
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

//    public function findOneBySomeField($value): ?ProductAdminInventory
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
