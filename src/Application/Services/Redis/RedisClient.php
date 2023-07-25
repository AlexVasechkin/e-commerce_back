<?php

namespace App\Application\Services\Redis;

use Predis;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RedisClient
{
    public Predis\Client $client;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->client = new Predis\Client($parameterBag->get('app.cache_dsn'));
    }
}
