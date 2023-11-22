<?php

namespace App\Repository;

use App\Entity\ProductGroupCategoryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @extends ServiceEntityRepository<ProductGroupCategoryItem>
 *
 * @method ProductGroupCategoryItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductGroupCategoryItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductGroupCategoryItem[]    findAll()
 * @method ProductGroupCategoryItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductGroupCategoryItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductGroupCategoryItem::class);
    }

    public function save(ProductGroupCategoryItem $entity, bool $flush = false): ProductGroupCategoryItem
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(ProductGroupCategoryItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdOrFail(string $id): ProductGroupCategoryItem
    {
        $entity = $this->findOneBy(['id' => Ulid::fromBase32($id)]);

        if (is_null($entity)) {
            throw new \Exception(sprintf('ProductGroupCategoryItem[id: %s] not found.', $id));
        }

        return $entity;
    }

    /**
     * @param array $productCategoryIdList
     * @return ProductGroupCategoryItem[]
     */
    public function fetchGroupsByCategories(array $productCategoryIdList): array
    {
        if ($productCategoryIdList === []) {
            return [];
        }

        return $this->createQueryBuilder('t')
            ->where('t.productCategory IN (:ids)')
            ->setParameter('ids', $productCategoryIdList)
            ->getQuery()
            ->getResult()
        ;
    }
}
