<?php

namespace App\Repository;

use App\Entity\ProductDispatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductDispatch>
 *
 * @method ProductDispatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDispatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDispatch[]    findAll()
 * @method ProductDispatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDispatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDispatch::class);
    }

//    /**
//     * @return ProductDispatch[] Returns an array of ProductDispatch objects
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

//    public function findOneBySomeField($value): ?ProductDispatch
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
