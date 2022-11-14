<?php

namespace App\Application\Services\ObjectStorage;

use AsyncAws\S3\S3Client;

class ObjectStorageAdapter
{
    public S3Client $client;

    public function __construct()
    {
        $this->client = new S3Client([
            'accessKeyId' => 'oaqXCboLopGkaWVgsXeXjN',
            'accessKeySecret' => '7HGG3KMdzKiqPtPs925uoEWkScK1NfK7SugE7DTrJ8dk',
            'region' => 'ru-central-1',
            'endpoint' => 'https://hb.bizmrg.com',
            'pathStyleEndpoint' => true,
        ]);
    }
}
