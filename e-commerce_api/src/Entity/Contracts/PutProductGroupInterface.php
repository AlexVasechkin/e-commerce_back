<?php

namespace App\Entity\Contracts;

interface PutProductGroupInterface
{
    public function getName(): string;

    public function isToHomepage(): bool;

    public function getHomepageSort(): int;
}
