<?php

namespace App\Domain\ValueObjects;

use App\Domain\Contracts\ProductCategoryInterface;

class ProductCategoryOrNull
{
    private $value;

    public function __construct($value)
    {
        $this->assertValue($value);
        $this->value = $value;
    }

    private function assertValue($value)
    {
        $success = is_null($value) || ($value instanceof ProductCategoryInterface);

        if (! $success) {
            throw new \InvalidArgumentException('Expected object of type ProductCategoryInterface or null');
        }
    }

    public function getValue(): ?ProductCategoryInterface
    {
        return $this->value;
    }
}
