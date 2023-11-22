<?php

namespace App\Application\Actions\User\DTO;

use App\Entity\Contracts\PutUserInterface;

class CreateUserRequest
    implements PutUserInterface
{
    private string $username;

    private string $email;

    private array $roles;

    private ?string $secret;

    public function __construct(
        string $username,
        string $email,
        array $roles,
        ?string $secret = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->roles = $roles;
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string|null
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }
}
