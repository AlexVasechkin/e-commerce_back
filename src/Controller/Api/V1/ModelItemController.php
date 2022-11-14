<?php

namespace App\Controller\Api\V1;

use App\Entity\ModelItem;
use App\Repository\ModelItemRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class ModelItemController extends AbstractController
{
    /**
     * @Route("/api/v1/private/model-item/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductRepository $productRepository,
        ProductCategoryRepository $productCategoryRepository,
        ModelItemRepository $modelItemRepository
    ) {
        $requestParams = $httpRequest->toArray();

        $product = $productRepository->findOneBy(['id' => $requestParams['productId']]);
        if (is_null($product)) {
            throw new Exception(sprintf('Product[id: %s] not found.', $requestParams['productId']));
        }

        $productCategory = $productCategoryRepository->findOneBy(['id' => $requestParams['productCategoryId']]);
        if (is_null($productCategory)) {
            throw new Exception(sprintf('ProductCategory[id: %s] not found.', $requestParams['productCategoryId']));
        }

        $modelItem = $modelItemRepository->findOneBy(['product' => $product, 'productCategory' => $productCategory]);
        if ($modelItem) {
            return $this->json(['id' => $modelItem->getId()->toBase32()]);
        }

        $modelItem = (new ModelItem())
            ->setProduct($product)
            ->setProductCategory($productCategory)
            ->setName($requestParams['name'])
        ;

        $modelItemRepository->save($modelItem, true);

        return $this->json(['id' => $modelItem->getId()->toBase32()]);
    }

    /**
     * @Route("/api/v1/private/model-item/{id}", methods={"GET"})
     */
    public function getInstance(
        $id,
        ModelItemRepository $modelItemRepository
    ) {
        $modelItem = $modelItemRepository->findOneBy(['id' => Ulid::fromBase32($id)]);
        if (is_null($modelItem)) {
            throw new NotFoundHttpException(sprintf('ModelItem[id: %s] not found.', $id));
        }

        return $this->json([
            'payload' => $this->serialize($modelItem)
        ]);
    }

    private function serialize(ModelItem $modelItem): array
    {
        return [
            'id' => $modelItem->getId()->toBase32(),
            'productId' => $modelItem->getProduct()->getId(),
            'productCategoryId' => $modelItem->getProductCategory()->getId(),
            'name' => $modelItem->getName()
        ];
    }

    /**
     * @Route("/api/v1/private/model-item/remove/{id}", methods={"GET"})
     */
    public function delete($id, ModelItemRepository $modelItemRepository)
    {
        $modelItem = $modelItemRepository->findOneBy(['id' => Ulid::fromBase32($id)]);
        if ($modelItem) {
            $modelItemRepository->remove($modelItem, true);
        }
    }
}
