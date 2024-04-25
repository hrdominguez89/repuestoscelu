<?php

namespace App\Repository;

use App\Entity\GPU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GPU>
 *
 * @method GPU|null find($id, $lockMode = null, $lockVersion = null)
 * @method GPU|null findOneBy(array $criteria, array $orderBy = null)
 * @method GPU[]    findAll()
 * @method GPU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GPURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GPU::class);
    }

//    /**
//     * @return GPU[] Returns an array of GPU objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GPU
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
