<?php

namespace App\Application\Services\Elasticsearch;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;

class Elasticsearch
{
    public Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['shop_elasticsearch:9200'])
            ->build()
        ;
    }
}
