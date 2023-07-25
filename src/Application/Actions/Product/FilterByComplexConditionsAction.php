<?php

namespace App\Application\Actions\Product;

use App\Repository\ProductRepository;
use Psr\Cache\CacheItemPoolInterface;

class FilterByComplexConditionsAction
{
    private CacheItemPoolInterface $cache;

    private ProductRepository $productRepository;

    private FilterProductsAction $filterProductsAction;

    public function __construct(
        CacheItemPoolInterface $cache,
        ProductRepository $productRepository,
        FilterProductsAction $filterProductsAction
    ) {
        $this->cache = $cache;
        $this->productRepository = $productRepository;
        $this->filterProductsAction = $filterProductsAction;
    }

    private function getFullIdList(): array
    {
        $cacheItem = $this->cache->getItem('full_product_id_list');

        if (!$cacheItem->isHit()) {
            $this->cache->save(
                $cacheItem
                    ->expiresAfter(60)
                    ->set(
                        $this->productRepository->getFullIdList()
                    )
            );
        }

        return $cacheItem->get();
    }

    public function execute(array $conditions): array
    {
        $idList = $this->getFullIdList();

        foreach ($conditions as $condition) {
            $isInclude = $condition['isInclude'] ?? true;
            if ($isInclude === false) {
                $idList = array_diff(
                    $idList,
                    $this->filterProductsAction->execute($condition['conditions'] ?? [])
                );
            } else {
                $idList = array_intersect(
                    $idList,
                    $this->filterProductsAction->execute($condition['conditions'] ?? [])
                );
            }
        }

        return $idList;
    }
}
