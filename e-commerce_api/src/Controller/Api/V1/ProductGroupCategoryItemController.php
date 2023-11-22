<?php

namespace App\Controller\Api\V1;

use App\Entity\ProductCategory;
use App\Entity\ProductGroup;
use App\Entity\ProductGroupCategoryItem;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductGroupCategoryItemRepository;
use App\Repository\ProductGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductGroupCategoryItemController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-group-category-item/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductGroupCategoryItemRepository $productGroupCategoryItemRepository,
        ProductGroupRepository $productGroupRepository,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $rp = $httpRequest->toArray();

        $entity = $productGroupCategoryItemRepository->findOneBy([
            'productGroup' => (new ProductGroup())->setId($rp['productGroupId']),
            'productCategory' => (new ProductCategory())->setId($rp['productCategoryId'])
        ]);

        if ($entity instanceof ProductGroupCategoryItem) {
            return $this->json([
                'id' => $entity->getId()->toBase32()
            ]);
        }

        $entity = $productGroupCategoryItemRepository->save(
            (new ProductGroupCategoryItem())
                ->setProductGroup($productGroupRepository->findOneByIdOrFail($rp['productGroupId']))
                ->setProductCategory($productCategoryRepository->findOneBy(['id' => $rp['productCategoryId'] ]))
                ->setSortOrder($rp['sortOrder'] ?? 500),
            true
        );

        return $this->json([
            'id' => $entity->getId()->toBase32()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-group-category-item/delete", methods={"POST"})
     */
    public function delete(
        Request $httpRequest,
        ProductGroupCategoryItemRepository $productGroupCategoryItemRepository
    ) {
        $rp = $httpRequest->toArray();

        $productGroupCategoryItemRepository->remove(
            $productGroupCategoryItemRepository->findOneByIdOrFail($rp['id']),
            true
        );

        return new Response('');
    }

    /**
     * @Route("/api/v1/private/product-group-category-item/list", methods={"POST"})
     */
    public function list(
        ProductGroupCategoryItemRepository $productGroupCategoryItemRepository,
        ProductGroupRepository $productGroupRepository,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $items = $productGroupCategoryItemRepository->findAll();

        $productGroupIdList = [];
        $productCategoryIdList = [];

        foreach ($items as $item) {
            $productGroupIdList[$item->getProductGroup()->getId()] = '';
            $productCategoryIdList[$item->getProductCategory()->getId()] = '';
        }

        $productGroupsDict = [];
        foreach ($productGroupRepository->fetchModelsById(array_keys($productGroupIdList)) as $productGroup) {
            $productGroupsDict[$productGroup->getId()] = $productGroup;
        }

        $productCategoryDict = [];
        foreach ($productCategoryRepository->fetchModelById(array_keys($productCategoryIdList)) as $productCategory) {
            $productCategoryDict[$productCategory->getId()] = $productCategory;
        }

        return $this->json([
            'payload' => array_map(function (ProductGroupCategoryItem $item) use ($productGroupsDict, $productCategoryDict) {

                /** @var ProductGroup $group */
                $group = $productGroupsDict[$item->getProductGroup()->getId()] ?? null;

                /** @var ProductCategory $category */
                $category = $productCategoryDict[$item->getProductCategory()->getId()] ?? null;

                return [
                    'id' => $item->getId()->toBase32(),
                    'productGroup' => [
                        'id' => $group ? $group->getId() : null,
                        'name' => $group ? $group->getName() : ''
                    ],
                    'productCategory' => [
                        'id' => $category ? $category->getId() : null,
                        'name' => $category ? $category->getName() : null
                    ],
                    'sortOrder' => $item->getSortOrder()
                ];
            }, $items)
        ]);
    }
}
