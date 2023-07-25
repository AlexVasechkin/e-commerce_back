<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductWebpage;
use App\Entity\Webpage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductWebpage>
 *
 * @method ProductWebpage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductWebpage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductWebpage[]    findAll()
 * @method ProductWebpage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductWebpageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductWebpage::class);
    }

    public function save(ProductWebpage $entity, bool $flush = false): ProductWebpage
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(ProductWebpage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterProductsByActivity(bool $activity): array
    {
        $q = implode(PHP_EOL, [
            "select distinct",
            "     pw.product_id as id",
            "  from product_webpage as pw",
            "  inner join webpage as w on w.id =pw.webpage_id",
            sprintf("          and w.is_active = %s", $activity ? 'true' : 'false'),
            ";",
        ]);

        return $this->getEntityManager()->getConnection()
            ->executeQuery($q)
            ->fetchFirstColumn()
        ;
    }

    public function filterProductsHasPage(): array
    {
        $q = implode(PHP_EOL, [
            "select distinct",
            "     t.product_id",
            "  from product_webpage as t"
        ]);

        return $this->getEntityManager()->getConnection()
            ->executeQuery($q)
            ->fetchFirstColumn()
        ;
    }

    public function findOneByProductAndWebpageOrFail(Product $product, Webpage $webpage): ProductWebpage
    {
        $entity = $this->findOneBy([
            'product' => $product,
            'webpage' => $webpage
        ]);

        if (is_null($entity)) {
            throw new \Exception(sprintf('ProductWebpage[product_id: %s, webpage_id: %s] not found', $product->getId(), $webpage->getId()));
        }

        return $entity;
    }
}
