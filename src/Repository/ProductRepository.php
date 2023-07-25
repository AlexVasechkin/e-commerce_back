<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Vendor;
use App\Message\IndexProductMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;
use function Doctrine\ORM\QueryBuilder;

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
    private MessageBusInterface $messageBus;

    public function __construct(
        ManagerRegistry $registry,
        MessageBusInterface $messageBus
    ) {
        parent::__construct($registry, Product::class);
        $this->messageBus = $messageBus;
    }

    public function save(Product $product): Product
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
        $this->messageBus->dispatch(new IndexProductMessage($product->getId()));
        return $product;
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

    public function findOneByIdOrFail(int $id): Product
    {
        $product = $this->findOneBy(['id' => $id]);

        if (!($product instanceof Product)) {
            throw new \Exception(sprintf('Product[id: %s] not found.', $id));
        }

        return $product;
    }

    public function findOneByCodeAndVendorOrFail(string $code, Vendor $vendor): Product
    {
        $product = $this->findOneBy(['code' => $code, 'vendor' => $vendor]);

        if (is_null($product)) {
            throw new \Exception(sprintf('Product[code: "%s", vendor_id: %s] not found', $code, $vendor->getId()));
        }

        return $product;
    }

    public function filterByVendors(array $vendorIdList): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->add('where', $qb->expr()->in('p.vendor', $vendorIdList));
        return $qb->select('p.id')
            ->getQuery()
            ->getSingleColumnResult()
        ;
    }

    public function filterToParsePrice(): array
    {
        $q = implode(PHP_EOL, [
            'select',
            '     p.id',
            '  from product as p',
            '  where p.parser_code is not null',
            '     and p.donor_url is not null',
            ';'
        ]);

        return $this
            ->getEntityManager()
            ->getConnection()
            ->executeQuery($q)
            ->fetchFirstColumn()
        ;
    }

    public function fetchProductData(array $idList): array
    {
        $qb = $this->createQueryBuilder('p');

//        $qb->innerJoin('p.vendor', 'v');

        $qb->add('where', $qb->expr()->in('p.id', $idList));

        $qb->select([
            'p.id'
        ]);
//        $qb->addSelect('p.id');
//        $qb->addSelect('p.code');
//        $qb->addSelect('p.price');
//        $qb->addSelect('v.name');

        return $qb->getQuery()
            ->getResult()
        ;
    }
}
