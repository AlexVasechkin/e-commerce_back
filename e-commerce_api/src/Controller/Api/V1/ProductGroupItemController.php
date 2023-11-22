<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\ProductGroupItem\CreateProductGroupItemAction;
use App\Application\Actions\ProductGroupItem\DTO\CreateProductGroupItemRequest;
use App\Entity\ProductGroup;
use App\Repository\ProductGroupItemRepository;
use App\Repository\ProductGroupRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductGroupItemController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-group-item/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductRepository $productRepository,
        ProductGroupRepository $productGroupRepository,
        CreateProductGroupItemAction $createProductGroupItemAction
    ) {
        $productGroupItem = $createProductGroupItemAction->execute(new CreateProductGroupItemRequest(
            $productRepository->findOneByIdOrFail($httpRequest->toArray()['productId'] ?? null),
            $productGroupRepository->findOneByIdOrFail($httpRequest->toArray()['productGroupId'] ?? null)
        ));

        return $this->json([
            'id' => $productGroupItem->getId()->toBase32()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-group-item/list", methods={"POST"})
     */
    public function list(
        Request $httpRequest,
        ProductGroupRepository $productGroupRepository,
        ProductRepository $productRepository,
        ProductGroupItemRepository $productGroupItemRepository
    ) {
        $groupList = $productGroupRepository->findAll();

        $groupItems = $productGroupItemRepository->fetchItemsByProduct(
            $productRepository->findOneByIdOrFail($httpRequest->toArray()['productId'] ?? null)
        );

        $itemsDict = [];
        foreach ($groupItems as $item) {
            $itemsDict[$item->getProductGroup()->getId()] = $item->getId()->toBase32();
        }

        return $this->json([
            'payload' => array_map(function (ProductGroup $productGroup) use ($itemsDict) {
                return [
                    'id' => $productGroup->getId(),
                    'name' => $productGroup->getName(),
                    'isInGroup' => isset($itemsDict[$productGroup->getId()])
                ];
            }, $groupList)
        ]);
    }

    /**
     * @Route("/api/v1/private/product-group-item/delete", methods={"POST"})
     */
    public function delete(
        Request $httpRequest,
        ProductRepository $productRepository,
        ProductGroupRepository $productGroupRepository,
        ProductGroupItemRepository $productGroupItemRepository
    ) {
        $productGroupItemRepository->remove(
            $productGroupItemRepository->findOneByProductAndGroupOrFail(
                $productRepository->findOneByIdOrFail($httpRequest->toArray()['productId'] ?? null),
                $productGroupRepository->findOneByIdOrFail($httpRequest->toArray()['productGroupId'] ?? null)
            ),
            true
        );

        return new Response('Ok');
    }
}
