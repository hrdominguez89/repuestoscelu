<?php

namespace App\Repository;

use App\Entity\ShippingType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShippingType>
 *
 * @method ShippingType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingType[]    findAll()
 * @method ShippingType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingType::class);
    }

//    /**
//     * @return ShippingType[] Returns an array of ShippingType objects
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

//    public function findOneBySomeField($value): ?ShippingType
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
