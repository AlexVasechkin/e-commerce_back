<?php

namespace App\Application\Services\Redis;

use Predis;

class RedisClient
{
    public Predis\Client $client;

    public function __construct()
    {
        $this->client = new Predis\Client('tcp://shop_redis:6379');
    }
}
