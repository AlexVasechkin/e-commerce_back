<?php

namespace App\Application\Services\JWT;

class ExtractPayloadService
{
    public function extractPayload(string $jwtToken): array
    {
        [1 => $payloadAsString] = explode('.', $jwtToken);
        is_string($payloadAsString) || $payloadAsString = '';
        return json_decode(base64_decode($payloadAsString), true);
    }
}
