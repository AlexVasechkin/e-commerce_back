<?php

namespace App\Application\Actions\Product;

use App\Application\Services\Redis\RedisClient;

class FilterByFullNameAction
{
    private RedisClient $redis;

    public function __construct(RedisClient $redis)
    {
        $this->redis = $redis;
    }

    private function reloadDataSet(): array
    {
        $r = [];

        foreach ($this->redis->client->keys('app:product:*:full-name') as $key) {
            $r[] = [
                'id' => intval(str_replace(':full-name', '', str_replace('app:product:', '', $key))),
                'name' => $this->redis->client->get($key)
            ];
        }

        return $r;
    }

    private function filterByWord(string $word, array &$dataSet): array
    {
        return array_values(array_column(array_filter($dataSet, function (array $item) use ($word) {
            return mb_strripos($item['name'], $word, 0, 'UTF-8') !== false;
        }), 'id'));
    }

    public function execute(string $searchString): array
    {
        $dataSet = $this->reloadDataSet();

        $words = explode(' ', $searchString);

        $resultIdList = array_column($dataSet, 'id');

        foreach ($words as $word) {
            $resultIdList = array_intersect(
                $resultIdList,
                $this->filterByWord($word, $dataSet)
            );
        }

        return array_values(array_unique($resultIdList));
    }
}
