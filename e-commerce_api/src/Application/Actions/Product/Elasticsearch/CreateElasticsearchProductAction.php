<?php

namespace App\Application\Actions\Product\Elasticsearch;

use App\Application\Services\Elasticsearch\Elasticsearch;
use App\Entity\Product;
use App\Repository\ProductRepository;

class CreateElasticsearchProductAction
{
    private Elasticsearch $es;

    private ProductRepository $productRepository;

    public function __construct(
        Elasticsearch $es,
        ProductRepository $productRepository
    ) {
        $this->es = $es;
        $this->productRepository = $productRepository;
    }

    public function execute(Product $product): void
    {
        $getProps = function () use ($product) {
            $result = [];

            foreach ($this->productRepository->filterPropertyIdList($product->getId()) as $propertyId) {
                $result[sprintf('p-%d', $propertyId)] = null;
            }

            return $result;
        };

        $categoryItem = $product->getProductCategoryItems()->first();
        $category = $categoryItem ? $categoryItem->getCategory() : null;
        $vendor = $product->getVendor();

        $this->es->client->create([
            'index' => 'products',
            'id' => $product->getId(),
            'body' => array_merge(
                [
                    'id' => $product->getId(),
                    'code' => $product->getCode(),
                    'name' => $product->getName(),
                    'category' => [
                        'id' => $category ? $category->getId() : null,
                        'name' => $category ? $category->getName() : null
                    ],
                    'vendor' => [
                        'id' => $vendor ? $vendor->getId() : null,
                        'name' => $vendor ? $vendor->getName() : null
                    ],
                    'props' => [], //$getProps(),
                ]
            )
        ]);
    }
}
