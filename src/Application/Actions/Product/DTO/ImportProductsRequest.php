<?php

namespace App\Application\Actions\Product\DTO;

use App\Entity\ProductCategory;

class ImportProductsRequest
{
    private array $dataSet = [];

    private ?ProductCategory $productCategory = null;

    public function getDataSet(): array
    {
        return $this->dataSet;
    }

    public function setDataSet(array $dataSet): self
    {
        $this->dataSet = $dataSet;
        return $this;
    }

    public function getProductCategory(): ?ProductCategory
    {
        return $this->productCategory;
    }

    public function setProductCategory(?ProductCategory $productCategory): self
    {
        $this->productCategory = $productCategory;
        return $this;
    }
}
