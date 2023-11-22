<?php

namespace App\Application\Actions\ProductGroupItem\DTO;

use App\Entity\Contracts\PutProductGroupItemInterface;
use App\Entity\Product;
use App\Entity\ProductGroup;

class CreateProductGroupItemRequest
    implements PutProductGroupItemInterface
{
    private Product $product;

    private ProductGroup $productGroup;

    public function __construct(
        Product $product,
        ProductGroup $productGroup
    ) {
        $this->product = $product;
        $this->productGroup = $productGroup;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductGroup(): ProductGroup
    {
        return $this->productGroup;
    }
}
