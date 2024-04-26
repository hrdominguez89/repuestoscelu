<?php

namespace App\Repository;

use App\Entity\ProductSalePointTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductSalePointTag>
 *
 * @method ProductSalePointTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSalePointTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSalePointTag[]    findAll()
 * @method ProductSalePointTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSalePointTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSalePointTag::class);
    }

    public function removeAllTags($product)
    {
        $qb = $this->createQueryBuilder('p')
            ->delete()
            ->where('p.product_sale_point = :product')
            ->setParameter('product', $product)
            ->getQuery()->execute();
    }

    //    /**
    //     * @return ProductSalePointTag[] Returns an array of ProductSalePointTag objects
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

    //    public function findOneBySomeField($value): ?ProductSalePointTag
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
