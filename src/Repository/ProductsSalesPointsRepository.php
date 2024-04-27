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

    /**
     * @return ProductsSalesPoints[] Returns an array of ProductsSalesPoints objects
     */
    public function findGeneralProductsBySalePoint($sale_point): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.product', 'pr')
            ->where('pr.sale_point != :prod_sale_point')
            ->andWhere('p.sale_point = :sale_point')
            ->setParameter('prod_sale_point', $sale_point)
            ->setParameter('sale_point', $sale_point)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findProductsByCategoryName($category_name, $limit = false): array
    { //falta agregar que disponible no sea null o mayor a 0
        $query = $this->createQueryBuilder('psp')
            ->join('psp.product', 'p')
            ->join('psp.sale_point', 'sp')
            ->join('psp.historicalPrices', 'hp')
            ->join('p.category', 'c')
            ->andWhere('c.name = :c_name')
            ->andWhere('sp.active = :sp_active')
            ->andWhere('sp.visible = :sp_visible')
            ->andWhere('p.visible = :p_visible')
            ->andWhere('hp.id IS NOT NULL')
            ->setParameter('c_name', $category_name)
            ->setParameter('sp_active', true)
            ->setParameter('sp_visible', true)
            ->setParameter('p_visible', true)
            ->orderBy('p.id', 'ASC');
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()
            ->getResult();
    }

    public function findProductsByTagName($tag_name, $limit = false): array
    { //falta agregar que disponible no sea null o mayor a 0
        $query = $this->createQueryBuilder('psp')
            ->join('psp.product', 'p')
            ->join('psp.sale_point', 'sp')
            ->join('psp.historicalPrices', 'hp')
            ->join('psp.productSalePointTags', 'pspt')
            ->join('pspt.tag', 't')
            ->andWhere('t.name = :t_name')
            ->andWhere('sp.active = :sp_active')
            ->andWhere('sp.visible = :sp_visible')
            ->andWhere('p.visible = :p_visible')
            ->andWhere('hp.id IS NOT NULL')
            ->setParameter('t_name', $tag_name)
            ->setParameter('sp_active', true)
            ->setParameter('sp_visible', true)
            ->setParameter('p_visible', true)
            ->orderBy('p.id', 'ASC');
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()
            ->getResult();
    }

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
