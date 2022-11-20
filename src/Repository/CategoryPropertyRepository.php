<?php

namespace App\Repository;

use App\Entity\CategoryProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryProperty>
 *
 * @method CategoryProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryProperty[]    findAll()
 * @method CategoryProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryPropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryProperty::class);
    }

    public function save(CategoryProperty $entity, bool $flush = false): CategoryProperty
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(CategoryProperty $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
