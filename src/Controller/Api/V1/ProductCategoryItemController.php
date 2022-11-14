<?php

namespace App\Controller\Api\V1;

use App\Entity\ProductCategoryItem;
use App\Repository\ProductCategoryItemRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductCategoryItemController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-category-item/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductCategoryItemRepository $productCategoryItemRepository,
        ProductCategoryRepository $productCategoryRepository,
        ProductRepository $productRepository
    ) {
        $requestParams = $httpRequest->toArray();

        $item = (new ProductCategoryItem())
            ->setProduct(
                $productRepository->findOneBy(['id' => $requestParams['productId']])
            )
            ->setCategory(
                $productCategoryRepository->findOneBy(['id' => $requestParams['categoryId']])
            )
        ;

        $productCategoryItemRepository->save($item, true);

        return $this->json([
            'id' => $item->getId()->toBase32()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category-item/delete", methods={"POST"})
     */
    public function delete(
        Request $httpRequest,
        ProductCategoryItemRepository $productCategoryItemRepository
    ) {
        $productCategoryItemRepository->remove(
            $productCategoryItemRepository->findOneBy(['id' => $httpRequest->toArray()['id']]),
            true
        );

        return $this->json([]);
    }
}
