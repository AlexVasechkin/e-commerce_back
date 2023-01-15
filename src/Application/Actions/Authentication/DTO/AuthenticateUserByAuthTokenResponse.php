<?php

namespace App\Application\Actions\Authentication\DTO;

use App\Entity\User;

class AuthenticateUserByAuthTokenResponse
{
    private ?User $user;

    private ?string $token;

    public function __construct()
    {
        $this->user = null;
        $this->token = null;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }
}
