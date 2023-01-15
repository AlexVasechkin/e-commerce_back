<?php

namespace App\Application\Actions\Authentication;

use App\Application\Services\JWT\ExtractPayloadService;
use App\Application\Services\Redis\RedisClient;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use stdClass;

class CheckAuthTokenAction
{
    private RedisClient $redis;

    private ExtractPayloadService $extractPayloadService;

    public function __construct(
        RedisClient $redis,
        ExtractPayloadService $extractPayloadService
    ) {
        $this->redis = $redis;
        $this->extractPayloadService = $extractPayloadService;
    }

    public function execute(string $token): stdClass
    {
        $payload = $this->extractPayloadService->extractPayload($token);
        $code = $payload['sub'] ?? null;
        if (!is_string($code)) {
            throw new Exception('payload.sub is empty!');
        }

        $key = $this->redis->client->get(sprintf('auth_email_%s', $code));
        if (!is_string($key)) {
            throw new Exception('Token data is empty.');
        }
        $key = unserialize($key);

        if (!is_array($key)) {
            throw new Exception('Invalid token data');
        }

        return JWT::decode($token, new Key($key['value'], 'HS512'));
    }
}
