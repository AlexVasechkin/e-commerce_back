<?php

namespace App\Entity\Contracts;

interface PutUserInterface
{
    public function getUserName(): string;

    public function getEmail(): string;

    public function getRoles(): array;

    public function getSecret(): ?string;
}
