<?php

namespace App\Repository;

use App\Entity\CategoryWebpage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryWebpage>
 *
 * @method CategoryWebpage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryWebpage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryWebpage[]    findAll()
 * @method CategoryWebpage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryWebpageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryWebpage::class);
    }

    public function save(CategoryWebpage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryWebpage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function fetchActiveWebpagesData(): array
    {
        return $this->createQueryBuilder('cw')
            ->join('cw.category', 'pc')
            ->join('cw.webpage', 'w')
            ->where('pc.isActive = :isActive')
            ->setParameter('isActive', true)
            ->select('pc.id', 'w.name', 'pc.nameSingle', 'pc.picture', 'w.alias')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCategoryWebpageByAlias(string $alias): ?CategoryWebpage
    {
        $resultSet = $this->createQueryBuilder('cw')
            ->innerJoin('cw.webpage', 'w')
            ->where('w.alias = :alias')
            ->setParameter('alias', $alias)
            ->select('cw.id')
            ->getQuery()
            ->getResult()
        ;

        if (isset($resultSet[0])) {
            return $this->findOneBy(['id' => $resultSet[0]['id']]);
        } else {
            return null;
        }
    }
}
