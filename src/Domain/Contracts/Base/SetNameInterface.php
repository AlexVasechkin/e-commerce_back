<?php

namespace App\Domain\Contracts\Base;

interface SetNameInterface
{
    public function setName(string $name): self;
}
