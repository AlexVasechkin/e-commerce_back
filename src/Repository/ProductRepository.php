<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductWebpage;
use App\Entity\Webpage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product): void
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
    }

    public function create(Product $product): Product
    {
        $this->save($product);
        return $product;
    }

    public function update(Product $product): Product
    {
        $this->save($product);
        return $product;
    }

    public function getFullIdList(): array
    {
        return array_map(function ($id) { return intval($id); }, array_column(
            $this->createQueryBuilder('t')
                ->select('t.id')
                ->getQuery()
                ->getResult(), 'id')
        );
    }

    public function fetchByIdList(array $idList): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.code', 't.name')
            ->where('t.id IN(:idList)')
            ->setParameter('idList', array_values($idList))
            ->getQuery()
            ->getResult()
        ;
    }

    public function filterPropertyIdList(int $productId): array
    {
        $query = implode(PHP_EOL, [
            "select distinct",
            "     cp.property_id as property_id",
            "  from product_category_item as pci",
            "  inner join product as p on p.id = pci.product_id",
            sprintf("                         and p.id = %d", $productId),
            "  inner join category_property as cp on cp.category_id = pci.category_id",
            ";",
        ]);

        return $this->getEntityManager()->getConnection()
            ->executeQuery($query)
            ->fetchFirstColumn()
        ;
    }

    public function fetchProductProperties(int $productId): array
    {
        $query = implode(PHP_EOL, [
            "select",
            "     cp.id          as category_property__id",
            "    ,prp.id         as property__id",
            "    ,prp.name       as property__name",
            "    ,cp.type        as type",
            "    ,cp.unit        as unit",
            "    ,cp.group_name  as group_name",
            "    ,v.string_value as string_value",
            "    ,v.int_value    as int_value",
            "    ,v.float_value  as float_value",
            "    ,v.bool_value   as bool_value",
            "  from product_category_item as pci",
            "  inner join product as p on p.id = pci.product_id",
            sprintf("                         and p.id = %d", $productId),
            "  inner join category_property as cp on cp.category_id = pci.category_id",
            "  left join property as prp on prp.id = cp.property_id",
            "  left join product_property_value as v on v.product_id = p.id",
            "                                       and v.property_id = prp.id",
            ";",
        ]);

        $resultSet = $this->getEntityManager()->getConnection()
            ->fetchAllAssociative($query)
        ;

        foreach ($resultSet as &$row) {
            $row['value'] = $row[sprintf('%s_value', $row['type'])] ?? null;
        }

        return $resultSet;
    }

    public function fetchProductsWithPages(): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.productWebpage', 'pw')
            ->innerJoin('pw.webpage', 'w')
            ->innerJoin('p.productCategoryItems', 'pci')
            ->where('w.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('p.count', 'desc')
            ->getQuery()->getResult()
        ;
    }


}
