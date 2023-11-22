<?php

namespace App\Application\Actions\Product;

use App\Application\Services\Elasticsearch\Elasticsearch;

class FilterByIndexAction
{
    private Elasticsearch $es;

    public function __construct(Elasticsearch $es)
    {
        $this->es = $es;
    }

    public function execute(array $conditions): array
    {
        $resultSet = [];

        $searchAfter = 0;

        do {
            $esResponse = $this->es->client->search([
                'index' => 'products',
                'size' => 10000,
                '_source' => false,
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => $conditions
                        ]
                    ],
                    'search_after' => [$searchAfter]
                ],
                'sort' => ['id']
            ])->asArray();

            $hits = ($esResponse['hits'] ?? [])['hits'] ?? [];

            if (count($hits) === 0) {
                break;
            }

            foreach ($hits as $hit) {
                if (isset($hit['_id'])) {
                    try {
                        $resultSet[] = intval($hit['_id']);
                        $searchAfter = intval($hit['_id']);
                    } catch (\Throwable $e) {}
                }
            }

        } while (true);

        return $resultSet;
    }
}
