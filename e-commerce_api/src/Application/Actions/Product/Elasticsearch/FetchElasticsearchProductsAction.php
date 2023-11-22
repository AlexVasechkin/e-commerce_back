<?php

namespace App\Application\Actions\Product\Elasticsearch;

use App\Application\Services\Elasticsearch\Elasticsearch;

class FetchElasticsearchProductsAction
{
    private Elasticsearch $es;

    public function __construct(Elasticsearch $es)
    {
        $this->es = $es;
    }

    public function execute(array $idList): array
    {
        $esResponse = $this->es->client->search([
            'index' => 'products',
            '_source' => true,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['terms' => [ 'id' => $idList ] ]
                        ]
                    ]
                ]
            ]
        ]);

        return array_map(function (array $item) {
            return $item['_source'] ?? [];
        }, ($esResponse->asArray()['hits'] ?? [])['hits'] ?? []);
    }
}
