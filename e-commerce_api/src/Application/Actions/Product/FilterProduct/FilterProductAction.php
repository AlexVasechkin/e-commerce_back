<?php

namespace App\Application\Actions\Product\FilterProduct;

use App\Application\Actions\Product\FilterProduct\DTO\FilterProductRequest;
use App\Application\Services\Elasticsearch\Elasticsearch;

class FilterProductAction
{
    private array $conditions = [];

    private Elasticsearch $es;

    public function __construct(Elasticsearch $es)
    {
        $this->es = $es;
    }

    public function execute(FilterProductRequest $request): array
    {
        if ($request->getName()) {
            $this->conditions[] = ['match' => ['name' => $request->getName()]];
        }

        if ($request->getCategoryId()) {
            $this->conditions[] = ['match' => ['category.id' => $request->getCategoryId()]];
        }

        if ($request->getVendorId()) {
            $this->conditions[] = ['match' => ['vendor.id' => $request->getVendorId()]];
        }

        foreach ($request->getProperties() as $key => $values) {
            $this->conditions[] = ['terms' => [ sprintf('props.p%s', $key) => $values ]];
        }

        $esResponse = $this->es->client->search([
            'index' => 'products',
            '_source' => false,
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => $this->conditions
                    ]
                ]
            ]
        ]);

        $hits = ($esResponse->asArray()['hits'] ?? [])['hits'] ?? [];
        $idList = [];
        foreach ($hits as $hit) {
            if (isset($hit['_id'])) {
                try {
                    $idList[] = intval($hit['_id']);
                } catch (\Throwable $e) {}
            }
        }

        return $idList;
    }
}
