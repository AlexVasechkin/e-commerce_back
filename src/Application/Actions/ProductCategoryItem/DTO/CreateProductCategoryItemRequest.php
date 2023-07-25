<?php

namespace App\Application\Actions\ProductCategoryItem\DTO;

use App\Entity\Contracts\PutProductCategoryItemInterface;
use App\Entity\Product;
use App\Entity\ProductCategory;

class CreateProductCategoryItemRequest
    implements PutProductCategoryItemInterface
{
    private Product $product;

    private ProductCategory $productCategory;

    public function __construct(Product $product, ProductCategory $productCategory)
    {
        $this->product = $product;
        $this->productCategory = $productCategory;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductCategory(): ProductCategory
    {
        return $this->productCategory;
    }
}
