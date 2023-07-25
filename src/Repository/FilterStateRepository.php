<?php

namespace App\Repository;

use App\Entity\FilterState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

/**
 * @extends ServiceEntityRepository<FilterState>
 *
 * @method FilterState|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilterState|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilterState[]    findAll()
 * @method FilterState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilterStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilterState::class);
    }

    public function save(FilterState $entity, bool $flush = true): FilterState
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(FilterState $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdOrFail(Ulid $id): FilterState
    {
        $entity = $this->findOneBy(['id' => $id]);

        if (is_null($entity)) {
            throw new \Exception(sprintf('FilterState[id: %s] not found!', $id->toBase32()));
        }

        return $entity;
    }
}
