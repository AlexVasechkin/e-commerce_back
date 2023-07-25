<?php

namespace App\Entity\Contracts;

use App\Entity\Product;
use App\Entity\ProductGroup;

interface PutProductGroupItemInterface
{
    public function getProduct(): Product;

    public function getProductGroup(): ProductGroup;
}
