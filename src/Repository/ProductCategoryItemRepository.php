<?php

namespace App\Repository;

use App\Entity\ProductCategoryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategoryItem>
 *
 * @method ProductCategoryItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategoryItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategoryItem[]    findAll()
 * @method ProductCategoryItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategoryItem::class);
    }

    public function save(ProductCategoryItem $entity, bool $flush = false): ProductCategoryItem
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(ProductCategoryItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
