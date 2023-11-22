<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductGroup;
use App\Entity\ProductGroupItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductGroupItem>
 *
 * @method ProductGroupItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductGroupItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductGroupItem[]    findAll()
 * @method ProductGroupItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductGroupItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductGroupItem::class);
    }

    public function save(ProductGroupItem $entity, bool $flush = false): ProductGroupItem
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(ProductGroupItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByProductAndGroupOrFail(Product $product, ProductGroup $productGroup): ProductGroupItem
    {
        $entity = $this->findOneBy([
            'product' => $product,
            'productGroup' => $productGroup
        ]);

        if (is_null($entity)) {
            throw new \Exception(sprintf('ProductGroupItem[product_id: %s, product_group_id: %s] not found.', $product->getId(), $productGroup->getId()));
        }

        return $entity;
    }

    /**
     * @param Product $product
     * @return ProductGroupItem[]
     */
    public function fetchItemsByProduct(Product $product): array
    {
        return $this->findBy(['product' => $product]);
    }

    public function filterProductsByGroups(array $productGroupIdList): array
    {
        if ($productGroupIdList === []) {
            return [];
        }

        return array_unique(array_map(fn(ProductGroupItem $item) => $item->getProduct()->getId(),
            $this->createQueryBuilder('pgi')
                ->where('pgi.productGroup IN(:ids)')
                ->setParameter('ids', $productGroupIdList)
                ->getQuery()
                ->getResult()
        ));
    }

    public function filterGroupsByProducts(array $productIdList): array
    {
        if ($productIdList === []) {
            return [];
        }

        return array_unique(array_map(fn(ProductGroupItem $item) => $item->getProductGroup()->getId(),
            $this->createQueryBuilder('pgi')
                ->where('pgi.product IN(:ids)')
                ->setParameter('ids', $productIdList)
                ->getQuery()
                ->getResult()
        ));
    }

    /**
     * @param array $productIdList
     * @return ProductGroupItem[]
     */
    public function fetchModelsByProducts(array $productIdList): array
    {
        return $this->createQueryBuilder('pgi')
            ->where('pgi.product IN(:ids)')
            ->setParameter('ids', $productIdList)
            ->getQuery()
            ->getResult()
        ;
    }
}
