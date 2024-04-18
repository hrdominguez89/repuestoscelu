<?php

namespace App\Repository;

use App\Entity\Subcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subcategory[]    findAll()
 * @method Subcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subcategory::class);
    }

    public function listSubcategories()
    {
        return $this->getEntityManager()
            ->createQuery('
            SELECT
            sc.id,
            c.name as category_name,
            sc.name,
            sc.slug,
            sc.visible,
            sc.created_at

            FROM App:Subcategory sc 
            LEFT JOIN App:Category c WITH sc.category = c.id
            ')
            ->getResult();
    }

    public function getSubcategoriesVisiblesByCategory($category)
    {

        return $this->createQueryBuilder('sc')
            ->select('sc.id', 'sc.name', 'sc.slug', "'sc=' as search_parameter")
            ->where('sc.visible = true')
            ->andWhere('sc.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }
}
