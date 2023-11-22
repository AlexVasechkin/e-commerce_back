<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\Product\Elasticsearch\DTO\UpdateElasticsearchProductRequest;
use App\Application\Actions\Product\Elasticsearch\UpdateElasticsearchProductAction;
use App\Entity\ProductCategoryItem;
use App\Repository\ProductCategoryItemRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        ProductRepository $productRepository,
        UpdateElasticsearchProductAction $updateElasticsearchProductAction
    ) {
        $requestParams = $httpRequest->toArray();

        $product = $productRepository->findOneBy(['id' => $requestParams['productId']]);

        $item = (new ProductCategoryItem())
            ->setProduct($product)
            ->setCategory(
                $productCategoryRepository->findOneBy(['id' => $requestParams['categoryId']])
            )
        ;

        $productCategoryItemRepository->save($item, true);

        $updateElasticsearchProductAction->execute(
            (new UpdateElasticsearchProductRequest($product->getId()))
                ->setProduct($product)
        );

        return $this->json([
            'id' => $item->getId()->toBase32()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category-item/delete", methods={"POST"})
     */
    public function delete(
        Request $httpRequest,
        ProductCategoryItemRepository $productCategoryItemRepository,
        UpdateElasticsearchProductAction $updateElasticsearchProductAction
    ) {
        $categoryItem = $productCategoryItemRepository->findOneBy(['id' => $httpRequest->toArray()['id']]);
        $product = $categoryItem->getProduct();

        $productCategoryItemRepository->remove(
            $categoryItem,
            true
        );

        $updateElasticsearchProductAction->execute(
            (new UpdateElasticsearchProductRequest($product->getId()))
                ->setProduct($product)
        );

        return $this->json([]);
    }

    /**
     * @Route("/api/v1/private/product-category-item/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        ProductCategoryItemRepository $productCategoryItemRepository,
        ProductCategoryRepository $productCategoryRepository,
        ProductRepository $productRepository,
        UpdateElasticsearchProductAction $updateElasticsearchProductAction
    ) {
        $rp = $httpRequest->toArray();

        $categoryItem = $productCategoryItemRepository->findOneBy(['id' => $rp['categoryItemId'] ?? null]);

        if (is_null($categoryItem)) {
            throw new NotFoundHttpException();
        }

        if ($rp['productCategoryId']) {
            $category = $productCategoryRepository->findOneBy(['id' => $rp['productCategoryId']]);

            if (is_null($category)) {
                throw new \Exception('Product category not found');
            }

            $categoryItem->setCategory($category);
            $productCategoryItemRepository->save($categoryItem, true);

            $product = $productRepository->findOneBy(['id' => $categoryItem->getProduct()]);
            $updateElasticsearchProductAction->execute(
                (new UpdateElasticsearchProductRequest($product->getId()))
                    ->setProduct($product)
            );
        }

        return $this->json([]);
    }

    /**
     * @Route("/api/v1/private/product-category-item/find-by-product", methods={"POST"})
     */
    public function findByProduct(
        Request $httpRequest,
        ProductRepository $productRepository,
        ProductCategoryItemRepository $productCategoryItemRepository
    ) {
        $rp = $httpRequest->toArray();
        $productId = $rp['productId'] ?? null;

        $product = $productRepository->findOneBy(['id' => $productId]);

        $categoryItem = $productCategoryItemRepository->findOneBy(['product' => $product]);

        if (is_null($categoryItem)) {
            throw new NotFoundHttpException();
        }

        $category = $categoryItem->getCategory();

        return $this->json([
            'categoryItemId' => $categoryItem->getId(),
            'productCategoryId' => $category ? $category->getId() : null
        ]);
    }
}
