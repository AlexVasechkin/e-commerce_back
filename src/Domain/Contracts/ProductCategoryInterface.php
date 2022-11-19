<?php

namespace App\Domain\Contracts;

use App\Domain\Contracts\Base;

interface ProductCategoryInterface extends Base\GetIntIdInterface,
                                           Base\GetSetNameInterface,
                                           Base\GetSetIsActiveInterface
{
    public function setParent(?ProductCategoryInterface $parent): self;

    public function getParent(): ?ProductCategoryInterface;
}
