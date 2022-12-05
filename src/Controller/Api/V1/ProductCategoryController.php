<?php

namespace App\Controller\Api\V1;

use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductCategoryController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-category/{$id}", methods={"GET"})
     */
    public function getInstance($id, ProductCategoryRepository $productCategoryRepository)
    {
        $productCategory = $productCategoryRepository->findOneBy(['id' => $id]);
        if (is_null($productCategory)) {
            throw new NotFoundHttpException(sprintf('ProductCategory[id: %s] not found', $id));
        }

        return $this->json([
            'payload' => $this->serialize($productCategory)
        ]);
    }

    private function serialize(ProductCategory $entity): array
    {
        return [
            'id' => $entity->getId(),
            'parentId' => $entity->getParent() ? $entity->getParent()->getId() : null,
            'name' => $entity->getName(),
            'isActive' => $entity->isActive()
        ];
    }

    /**
     * @Route("/api/v1/private/product-category/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $requestParams = $httpRequest->toArray();

        $pc = (new ProductCategory())
            ->setName($requestParams['name'] ?? null)
            ->setIsActive(true)
        ;

        if (isset($requestParams['parentId'])) {
            $parentCategory = $productCategoryRepository->findOneBy(['id' => $requestParams['parentId']]);
            if ($parentCategory) {
                $pc->setParent($parentCategory);
            }
        }

        $productCategoryRepository->save($pc, true);

        return $this->json([
            'id' => $pc->getId()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category/list", methods={"GET"})
     */
    public function getList(
        Connection $connection
    ) {
        $query = implode(PHP_EOL, [
            'select',
            '     pc.id        as id',
            '    ,pc.name      as name',
            '    ,pc.is_active as is_active',
            '    ,p.id         as parent_id',
            '    ,p.name       as parent_name',
            '  from product_category as pc',
            '  left join product_category as p on p.id = pc.parent_id',
            '  order by',
            '     pc.is_active desc',
            '    ,pc.name',
            '    ,p.name',
            ';'
        ]);

        return $this->json([
            'payload' => array_map(function (array $item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'isActive' => $item['is_active'],
                    'parentId' => $item['parent_id'],
                    'parentName' => $item['parent_name']
                ];
            }, $connection->fetchAllAssociative($query))
        ]);
    }

    private function serializeForDict(ProductCategory $entity): array
    {
        return [
            'value' => $entity->getId(),
            'caption' => $entity->getName()
        ];
    }

    /**
     * @Route("/api/v1/private/product-category/dict", methods={"GET"})
     */
    public function getDict(
        ProductCategoryRepository $productCategoryRepository
    ) {
        return $this->json([
            'payload' => array_map(function (ProductCategory $pc) {
                return $this->serializeForDict($pc);
            }, $productCategoryRepository->findAll())
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $rp = $httpRequest->toArray();

        $id = $rp['id'] ?? null;

        $pc = $productCategoryRepository->findOneBy(['id' => $id]);

        if (is_null($pc)) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        isset($rp['name']) ? $pc->setName($rp['name']) : null;

        isset($rp['isActive']) ? $pc->setIsActive($rp['isActive']) : null;

        if (isset($rp['parentId'])) {
            $p = $productCategoryRepository->findOneBy(['id' => $rp['parentId']]);
            $pc->setParent($p);
        }

        $productCategoryRepository->save($pc, true);

        return $this->json(['payload' => $this->serialize($pc)]);
    }
}
