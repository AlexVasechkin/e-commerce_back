<?php

namespace App\Application\Actions\Product;

use App\Entity\ProductCategoryItem;
use App\Repository\CategoryWebpageRepository;
use App\Repository\ProductCategoryItemRepository;
use App\Repository\ProductGroupItemRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductWebpageRepository;

class FilterProductsAction
{
    private ProductRepository $productRepository;

    private ProductWebpageRepository $productWebpageRepository;

    private CategoryWebpageRepository $categoryWebpageRepository;

    private ProductCategoryItemRepository $productCategoryItemRepository;

    private ProductGroupItemRepository $productGroupItemRepository;

    private FilterByFullNameAction $filterByFullNameAction;

    public function __construct(
        ProductRepository $productRepository,
        ProductWebpageRepository $productWebpageRepository,
        CategoryWebpageRepository $categoryWebpageRepository,
        ProductCategoryItemRepository $productCategoryItemRepository,
        ProductGroupItemRepository $productGroupItemRepository,
        FilterByFullNameAction $filterByFullNameAction
    ) {
        $this->productRepository = $productRepository;
        $this->productWebpageRepository = $productWebpageRepository;
        $this->categoryWebpageRepository = $categoryWebpageRepository;
        $this->productCategoryItemRepository = $productCategoryItemRepository;
        $this->productGroupItemRepository = $productGroupItemRepository;
        $this->filterByFullNameAction = $filterByFullNameAction;
    }

    public function execute(array $filters): array
    {
        $resultSet = $this->productRepository->getFullIdList();

        if (isset($filters['categoryId']) && is_int($filters['categoryId'])) {
            $resultSet = array_intersect(
                $resultSet,
                $this->productCategoryItemRepository->filterByCategories([$filters['categoryId']])
            );
        }

        if (   isset($filters['categoryAlias'])
            && is_string($filters['categoryAlias'])
        ) {
            $categoryWebpage = $this->categoryWebpageRepository->findCategoryWebpageByAlias($filters['categoryAlias']);

            if ($categoryWebpage) {
                $resultSet = array_intersect(
                    $resultSet,
                    $categoryWebpage->getCategory()->getProductCategoryItems()->map(
                        fn(ProductCategoryItem $categoryItem) => $categoryItem->getProduct()->getId()
                    )->toArray()
                );
            } else {
                $resultSet = [];
            }
        }

        if (   isset($filters['productPageActive'])
            && is_bool($filters['productPageActive'])
        ) {
            $resultSet = array_intersect(
                $resultSet,
                $this->productWebpageRepository->filterProductsByActivity($filters['productPageActive'])
            );
        }

        if (   isset($filters['vendorIdList'])
            && is_array($filters['vendorIdList'])
        ) {
            if ($filters['vendorIdList'] !== []) {
                $resultSet = array_intersect(
                    $resultSet,
                    $this->productRepository->filterByVendors($filters['vendorIdList'])
                );
            }
        }

        if (   isset($filters['productGroupIdList'])
            && is_array($filters['productGroupIdList'])
        ) {
            if ($filters['productGroupIdList'] !== []) {
                $resultSet = array_intersect(
                    $resultSet,
                    $this->productGroupItemRepository->filterProductsByGroups($filters['productGroupIdList'])
                );
            }
        }

        if (   isset($filters['searchString'])
            && is_string($filters['searchString'])
            && (strlen($filters['searchString']) > 0)
        ) {
            $resultSet = array_intersect(
                $resultSet,
                $this->filterByFullNameAction->execute($filters['searchString'])
            );
        }

        if (   !empty($filters['productHasPage'])
            && is_bool($filters['productHasPage'])
        ) {
            $resultSet = $filters['productHasPage']
                ? array_intersect($resultSet, $this->productWebpageRepository->filterProductsHasPage())
                : array_diff($resultSet, $this->productWebpageRepository->filterProductsHasPage())
            ;
        }

        return array_values($resultSet);
    }
}
