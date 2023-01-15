<?php

namespace App\Application\Actions\Authentication\DTO;

class AuthenticateUserByAuthTokenRequest
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
