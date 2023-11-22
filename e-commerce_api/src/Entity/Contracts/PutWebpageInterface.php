<?php

namespace App\Entity\Contracts;

use App\Entity\Webpage;

interface PutWebpageInterface
{
    public function getName(): string;

    public function getPagetitle(): ?string;

    public function getHeadline(): ?string;

    public function getDescription(): ?string;

    public function getContent(): ?string;

    public function getAlias(): string;

    public function getParent(): ?Webpage;

    public function isActive(): bool;
}
