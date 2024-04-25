<?php

namespace App\Repository;

use App\Entity\ScreenSize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScreenSize>
 *
 * @method ScreenSize|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScreenSize|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScreenSize[]    findAll()
 * @method ScreenSize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScreenSizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScreenSize::class);
    }

//    /**
//     * @return ScreenSize[] Returns an array of ScreenSize objects
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

//    public function findOneBySomeField($value): ?ScreenSize
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
