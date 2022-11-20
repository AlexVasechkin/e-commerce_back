<?php

namespace App\Application\Services\ObjectStorage;

use AsyncAws\S3\S3Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ObjectStorageAdapter
{
    public S3Client $client;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->client = new S3Client([
            'accessKeyId' => $parameterBag->get('app.object_storage.key_id'),
            'accessKeySecret' => $parameterBag->get('app.object_storage.secret_key'),
            'region' => 'ru-central-1',
            'endpoint' => 'https://hb.bizmrg.com',
            'pathStyleEndpoint' => true,
        ]);
    }
}
