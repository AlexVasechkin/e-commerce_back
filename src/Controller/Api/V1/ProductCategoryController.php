<?php

namespace App\Controller\Api\V1;

use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
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
            'code' => $entity->getCode(),
            'name' => $entity->getName(),
            'isActive' => $entity->isActive()
        ];
    }

    /**
     * @Route("/api/v1/private/product-category/create", methods={"POST"})
     */
    private function create(
        Request $httpRequest,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $requestParams = $httpRequest->toArray();

        $pc = (new ProductCategory())
            ->setCode($requestParams['code'] ?? null)
            ->setName($requestParams['name'] ?? null)
            ->setIsActive(true)
        ;

        if (isset($requestParams['parentId'])) {
            $parentCategory = $productCategoryRepository->findOneBy(['id' => $requestParams['parentId']]);
            if ($parentCategory) {
                $pc->setParent($parentCategory);
            }
        }

        $productCategoryRepository->save($pc);

        return $this->json([
            'payload' => $this->serialize($pc)
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category/list", methods={"GET"})
     */
    public function getList(
        ProductCategoryRepository $productCategoryRepository
    ) {
        return $this->json([
            'payload' => array_map(function (ProductCategory $pc) {
                return $this->serialize($pc);
            }, $productCategoryRepository->findAll())
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

        isset($rp['code']) ? $pc->setCode($rp['code']) : null;

        isset($rp['name']) ? $pc->setName($rp['name']) : null;

        isset($rp['isActive']) ? $pc->setIsActive($rp['isActive']) : null;

        if (isset($rp['parentId'])) {
            $p = $productCategoryRepository->findOneBy(['id' => $rp['parentId']]);
            if ($p) {
                $pc->setParent($p);
            }
        }

        $productCategoryRepository->save($pc);

        return $this->json(['payload' => $this->serialize($pc)]);
    }
}
