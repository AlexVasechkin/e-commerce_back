<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductPropertyValue;
use App\Message\IndexProductMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @extends ServiceEntityRepository<ProductPropertyValue>
 *
 * @method ProductPropertyValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPropertyValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPropertyValue[]    findAll()
 * @method ProductPropertyValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPropertyValueRepository extends ServiceEntityRepository
{
    private MessageBusInterface $messageBus;

    public function __construct(
        ManagerRegistry $registry,
        MessageBusInterface $messageBus
    ) {
        parent::__construct($registry, ProductPropertyValue::class);
        $this->messageBus = $messageBus;
    }

    public function save(ProductPropertyValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        $this->messageBus->dispatch(new IndexProductMessage($entity->getProduct()->getId()));
    }

    public function remove(ProductPropertyValue $entity, bool $flush = false): void
    {
        $productId = $entity->getProduct()->getId();

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        $this->messageBus->dispatch(new IndexProductMessage($productId));
    }

    public function fetchByProductAndProperties(Product $product, array $props)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->where('t.product = :product')
            ->andWhere('t.property IN (:props)')
            ->setParameter('product', $product)
            ->setParameter('props', $props)
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
