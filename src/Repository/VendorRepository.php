<?php

namespace App\Repository;

use App\Entity\Vendor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vendor>
 *
 * @method Vendor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vendor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vendor[]    findAll()
 * @method Vendor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VendorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vendor::class);
    }

    public function save(Vendor $entity, bool $flush = false): Vendor
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function remove(Vendor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByNameOrFail(string $name): Vendor
    {
        $vendor = $this->findOneBy(['name' => $name]);

        if (is_null($vendor)) {
            throw new \Exception(sprintf('Vendor[name: "%s"] not found!', $name));
        }

        return $vendor;
    }

    public function fetchDataForDict(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.name')
            ->getQuery()
            ->getResult()
        ;
    }
}
