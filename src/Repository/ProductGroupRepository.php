<?php

namespace App\Repository;

use App\Entity\ProductGroup;
use App\Entity\ProductGroupItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductGroup>
 *
 * @method ProductGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductGroup[]    findAll()
 * @method ProductGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductGroup::class);
    }

    public function save(ProductGroup $entity, bool $flush = false): ProductGroup
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(ProductGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByOrFail(array $criteria, string $message): ProductGroup
    {
        $entity = $this->findOneBy($criteria);

        if (is_null($entity)) {
            throw new \Exception($message);
        }

        return $entity;
    }

    public function findOneByNameOrFail(string $name): ProductGroup
    {
        return $this->findOneByOrFail(
            ['name' => $name],
            sprintf('ProductGroup[name: "%s"] not found.', $name)
        );
    }

    public function findOneByIdOrFail(int $id): ProductGroup
    {
        return $this->findOneByOrFail(
            ['id' => $id],
            sprintf('ProductGroup[id: %s] not found.', $id)
        );
    }

    /**
     * @param array $productGroupIdList
     * @return ProductGroup[]
     */
    public function fetchModelsById(array $productGroupIdList): array
    {
        if ($productGroupIdList === []) {
            return [];
        }

        return $this
            ->createQueryBuilder('pg')
            ->where('pg.id IN (:ids)')
            ->setParameter('ids', $productGroupIdList)
            ->getQuery()
            ->getResult()
        ;
    }
}
