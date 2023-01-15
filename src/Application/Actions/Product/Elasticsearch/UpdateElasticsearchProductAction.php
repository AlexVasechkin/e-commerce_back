<?php

namespace App\Application\Actions\Product\Elasticsearch;

use App\Application\Actions\Product\Elasticsearch\DTO\UpdateElasticsearchProductRequest;
use App\Application\Services\Elasticsearch\Elasticsearch;
use App\Repository\ProductRepository;

class UpdateElasticsearchProductAction
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

    public function execute(UpdateElasticsearchProductRequest $request): void
    {
        $body = $this->es->client
            ->getSource(['id' => $request->getProductId(), 'index' => 'products'])
            ->asArray()
        ;

        if ($request->getProduct()) {
            $body['code'] = $request->getProduct()->getCode() ?? '';
            $body['name'] = $request->getProduct()->getName() ?? '';
            $body['count'] = round($request->getProduct()->getCount() ?? 0);
            $body['length'] = round($request->getProduct()->getLength() ?? 0); // mm
            $body['width'] = round($request->getProduct()->getWidth() ?? 0); // mm
            $body['height'] = round($request->getProduct()->getHeight() ?? 0); // mm
            $body['mass'] = round($request->getProduct()->getMass() ?? 0); // mg

            $categoryItem = $request->getProduct()->getProductCategoryItems()->first();
            $category = $categoryItem ? $categoryItem->getCategory() : null;
            $body['category'] = [
                'id' => $category ? $category->getId() : null,
                'name' => $category ? $category->getName() : null
            ];

            $vendor = $request->getProduct()->getVendor();
            $body['vendor'] = [
                'id' => $vendor ? $vendor->getId() : null,
                'name' => $vendor ? $vendor->getName() : null
            ];
        }

        $properties = $this->productRepository->fetchProductProperties($request->getProductId());
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

        $this->es->client->update([
            'id' => strval($request->getProductId()),
            'index' => 'products',
            'body' => [
                'doc' => $body
            ]
        ]);
    }
}
