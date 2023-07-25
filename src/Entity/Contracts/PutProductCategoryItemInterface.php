<?php

namespace App\Entity\Contracts;

use App\Entity\Product;
use App\Entity\ProductCategory;

interface PutProductCategoryItemInterface
{
    public function getProduct(): Product;

    public function getProductCategory(): ProductCategory;
}
