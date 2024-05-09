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
    {
        $query = $this->createQueryBuilder('psp')
            ->select('DISTINCT psp, random() as HIDDEN rand')
            ->join('psp.product', 'p')
            ->join('psp.sale_point', 'sp')
            ->join('psp.historicalPrices', 'hp')
            ->join('psp.productSalePointInventories', 'pspi')
            ->join('p.category', 'c')
            ->andWhere('c.name = :c_name')
            ->andWhere('sp.active = :sp_active')
            ->andWhere('sp.visible = :sp_visible')
            ->andWhere('p.visible = :p_visible')
            ->andWhere('hp.id IS NOT NULL')
            ->andWhere('pspi.id IS NOT NULL')
            ->setParameter('c_name', $category_name)
            ->setParameter('sp_active', true)
            ->setParameter('sp_visible', true)
            ->setParameter('p_visible', true)
            ->orderBy('rand');
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()
            ->getResult();
    }

    public function findProductsByTagName($tag_name, $limit = false): array
    {
        $query = $this->createQueryBuilder('psp')
            ->select('DISTINCT psp, random() as HIDDEN rand')
            ->join('psp.product', 'p')
            ->join('psp.sale_point', 'sp')
            ->join('psp.historicalPrices', 'hp')
            ->join('psp.productSalePointTags', 'pspt')
            ->join('psp.productSalePointInventories', 'pspi')
            ->join('pspt.tag', 't')
            ->andWhere('t.name = :t_name')
            ->andWhere('sp.active = :sp_active')
            ->andWhere('sp.visible = :sp_visible')
            ->andWhere('p.visible = :p_visible')
            ->andWhere('hp.id IS NOT NULL')
            ->andWhere('pspi.id IS NOT NULL')
            ->setParameter('t_name', $tag_name)
            ->setParameter('sp_active', true)
            ->setParameter('sp_visible', true)
            ->setParameter('p_visible', true)
            ->orderBy('rand');
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()
            ->getResult();
    }


    public function findActiveProductById($id): ?ProductsSalesPoints
    {
        $query = $this->createQueryBuilder('psp')
            ->select('DISTINCT psp')
            ->join('psp.product', 'p')
            ->join('psp.sale_point', 'sp')
            ->join('psp.historicalPrices', 'hp')
            ->where('psp.id = :psp_id')
            ->andWhere('sp.active = :sp_active')
            ->andWhere('sp.visible = :sp_visible')
            ->andWhere('p.visible = :p_visible')
            ->andWhere('hp.id IS NOT NULL')
            ->setParameter('psp_id', $id)
            ->setParameter('sp_active', true)
            ->setParameter('sp_visible', true)
            ->setParameter('p_visible', true);
        return $query->getQuery()
            ->getOneOrNullResult();
    }

    public function findProductByFilters($keywords = null, $filters = null, $limit = 8, $index = 0)
    {
        $products = $this->createQueryBuilder('psp')
            ->join('psp.product', 'p')
            ->join('psp.sale_point', 'sp')
            ->join('p.category', 'c')
            ->join('p.subcategory', 'sc')
            ->join('psp.historicalPrices', 'hp')
            ->where('p.visible = :p_visible')
            ->andWhere('hp.id IS NOT NULL')
            ->andWhere('sp.active = :sp_active')
            ->andWhere('sp.visible = :sp_visible');
        if ($keywords) {
            $orX = $products->expr()->orX();
            $i = 0;
            $subquery = '';
            $sumaCoincidencias = '';
            foreach ($keywords as $keyword) {
                if ($i > 0) {
                    $sumaCoincidencias.=',';
                    $subquery .= ', ';
                }
                $subquery .= "
                '(SELECT 
                    CASE 
                        WHEN EXISTS (SELECT 1 FROM p WHERE clearstr(p.name) LIKE %clearstr(" . $keyword . ")%) 
                        THEN 1 
                        ELSE 0 
                    END)' AS HIDDEN result_" . $i;
                $sumaCoincidencias.="result_".$i;
                $orX->add(
                    $products->expr()->orX(
                        $products->expr()->like("clearstr(p.name)", "clearstr(:keyword_name_" . $i . ")"),
                        $products->expr()->like("clearstr(p.description)", "clearstr(:keyword_description_" . $i . ")")
                    )
                );
                $products->setParameter('keyword_name_' . $i, "%" . $keyword . "%");
                $products->setParameter('keyword_description_' . $i, "%" . $keyword . "%");
                $i++;
            }
            $products->andWhere($orX);
            $products->select("DISTINCT psp, " . $subquery);
            $products->orderBy($sumaCoincidencias, 'DESC');
        } else {
            $products->select("DISTINCT psp");
        }
        if ($filters) {
            foreach ($filters as $filter) {
                if ($filter['name'] == 'tag') {
                    $products->join('psp.productSalePointTags', 'pspt');
                }
                $products->andWhere($filter['table'] . '.' . $filter['name'] . ' = :' . $filter['table'] . '_' . $filter['name']);
                $products->setParameter($filter['table'] . '_' . $filter['name'], $filter['value']);
            }
        }
        $products->setParameter('sp_active', true)
            ->setParameter('sp_visible', true)
            ->setParameter('p_visible', true);
        if ($index) {
            $products->setFirstResult($index);
        }
        $products->setMaxResults($limit);
        // dd($products->getQuery()->getDQL());
        return $products->getQuery()->getResult();
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
