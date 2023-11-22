<?php

namespace App\Application\Actions\Product;

use App\Application\Services\Redis\RedisClient;
use App\Repository\ProductRepository;

class IndexProductAction
{
    private ProductRepository $productRepository;

    private RedisClient $redis;

    public function __construct(
        ProductRepository $productRepository,
        RedisClient $redis
    ) {
        $this->productRepository = $productRepository;
        $this->redis = $redis;
    }

    public function execute(int $productId)
    {
        $product = $this->productRepository->findOneByIdOrFail($productId);

        $body = [];

        $body['id'] = $product->getId() ?? 0;
        $body['code'] = $product->getCode() ?? '';
        $body['name'] = $product->getName() ?? '';
        $body['count'] = intval(round($product->getCount() ?? 0));
        $body['length'] = intval(round($product->getLength() ?? 0)); // mm
        $body['width'] = intval(round($product->getWidth() ?? 0)); // mm
        $body['height'] = intval(round($product->getHeight() ?? 0)); // mm
        $body['mass'] = intval(round($product->getMass() ?? 0)); // mg
        $body['price'] = $product->getPrice() ?? 0;

        $body['category'] = [];
        foreach ($product->getProductCategoryItems() as $categoryItem) {
            $category = $categoryItem->getCategory();
            $body['category'][] = [
                'id' => $category ? $category->getId() : null,
                'name' => $category ? $category->getName() : null,
                'name_single' => $category ? $category->getNameSingle() : null
            ];
        }

        $vendor = $product->getVendor();
        $body['vendor'] = [
            'id' => $vendor ? $vendor->getId() : null,
            'name' => $vendor ? $vendor->getName() : null
        ];

        $properties = $this->productRepository->fetchProductProperties($productId);
        $body['properties'] = [];
        $body['props'] = [];
        foreach ($properties as $property) {
            switch ($property['type']) {
                case 'float':
                    $value = $property['value'] ? strval(round($property['value'], 2)) : null;
                    break;
                case 'int':
                    $value = $property['value'] ? intval($property['value']) : null;
                    break;
                case 'bool':
                    $value = $property['value'] ? intval($property['value']) : null;
                    break;
                default:
                    $value = $property['value'] ? strval($property['value']) : null;
            }

            $body['properties'][] = [
                'id' => $property['property__id'] ?? null,
                'name' => $property['property__name'] ?? null,
                'type' => $property['type'] ?? null,
                'unit' => $property['unit'] ?? null,
                'group' => $property['group_name'] ?? null,
                'value' => $value,
            ];

            $body['props'][sprintf('p%d', $property['property__id'])] = $value;
        }

        $category = $product->getProductCategoryItems()->first();
        $category = $category ? $category->getCategory() : null;
        $body['full_name'] = trim(implode(' ', [
            $category ? $category->getNameSingle() : '',
            $vendor ? $vendor->getName() : '',
            $product->getName() ?? '',
            $product->getCode() ?? ''
        ]));

        $this->redis->client->set(sprintf('app:product:%s:full-name', $product->getId()), $body['full_name']);

//        $this->es->client->index([
//            'index' => 'products',
//            'id' => $productId,
//            'body' => $body
//        ]);
    }
}
