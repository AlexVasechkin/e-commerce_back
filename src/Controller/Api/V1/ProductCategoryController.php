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
     * @Route("/api/v1/private/product-category/{$id}")
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
    }


}
