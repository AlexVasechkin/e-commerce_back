<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}
