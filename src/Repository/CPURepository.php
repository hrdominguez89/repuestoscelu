<?php

namespace App\Repository;

use App\Entity\CPU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CPU>
 *
 * @method CPU|null find($id, $lockMode = null, $lockVersion = null)
 * @method CPU|null findOneBy(array $criteria, array $orderBy = null)
 * @method CPU[]    findAll()
 * @method CPU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CPURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CPU::class);
    }

//    /**
//     * @return CPU[] Returns an array of CPU objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CPU
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
