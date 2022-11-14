<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductPropertyValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductPropertyValue>
 *
 * @method ProductPropertyValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPropertyValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPropertyValue[]    findAll()
 * @method ProductPropertyValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPropertyValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductPropertyValue::class);
    }

    public function save(ProductPropertyValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductPropertyValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function fetchByProductAndProperties(Product $product, array $props)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->where('t.product = :product')
            ->andWhere('t.property IN (:props)')
            ->setParameter('product', $product)
            ->setParameter('props', $props)
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
