<?php

namespace App\Repository;

use App\Entity\ModelItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelItem>
 *
 * @method ModelItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelItem[]    findAll()
 * @method ModelItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelItem::class);
    }

    public function save(ModelItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModelItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
