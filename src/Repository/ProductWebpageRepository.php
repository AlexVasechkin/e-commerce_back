<?php

namespace App\Repository;

use App\Entity\ProductWebpage;
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

    public function save(ProductWebpage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductWebpage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
