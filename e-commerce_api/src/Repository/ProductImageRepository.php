<?php

namespace App\Repository;

use App\Entity\ProductImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductImage>
 *
 * @method ProductImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductImage[]    findAll()
 * @method ProductImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    public function save(ProductImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function fetchProductImages(int $productId): array
    {
        $query = implode(PHP_EOL, [
            "select",
            "     i.id",
            "    ,i.path",
            "    ,i.is_deleted",
            "    ,i.sort_order",
            "  from product_image as i",
            sprintf("  where i.product_id = %d", $productId),
            "  order by",
            "    i.sort_order",
            ";",
        ]);

        return $this->getEntityManager()->getConnection()
            ->fetchAllAssociative($query)
        ;
    }

    public function fetchImages(bool $onlyActive = true): array
    {
        $q = implode(PHP_EOL, [
            "select",
            "     pi.id",
            "    ,pi.product_id",
            "    ,pi.path",
            "    ,pi.description",
            "    ,pi.sort_order",
            "  from product_image as pi",
            $onlyActive ? "  where pi.is_deleted = cast(0 as bool)" : '',
            "  order by",
            "     pi.product_id",
            "    ,pi.sort_order",
            ";",
        ]);

        return $this->getEntityManager()->getConnection()
            ->fetchAllAssociative($q)
        ;
    }
}
