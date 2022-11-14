<?php

namespace App\Domain\Contracts\Base;

interface SetCodeInterface
{
    public function setCode(string $code): self;
}
