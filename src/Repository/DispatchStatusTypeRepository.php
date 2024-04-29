<?php

namespace App\Repository;

use App\Entity\DispatchStatusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DispatchStatusType>
 *
 * @method DispatchStatusType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DispatchStatusType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DispatchStatusType[]    findAll()
 * @method DispatchStatusType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispatchStatusTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DispatchStatusType::class);
    }

//    /**
//     * @return DispatchStatusType[] Returns an array of DispatchStatusType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DispatchStatusType
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
