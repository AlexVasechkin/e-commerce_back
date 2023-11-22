<?php

namespace App\Domain\Contracts\Base;

interface SetIsActiveInterface
{
    public function setIsActive(bool $setIsActive): ?self;
}