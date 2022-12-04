<?php

namespace App\Repository;

use App\Entity\Webpage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Webpage>
 *
 * @method Webpage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Webpage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Webpage[]    findAll()
 * @method Webpage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebpageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Webpage::class);
    }

    public function save(Webpage $entity, bool $flush = false): Webpage
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(Webpage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
