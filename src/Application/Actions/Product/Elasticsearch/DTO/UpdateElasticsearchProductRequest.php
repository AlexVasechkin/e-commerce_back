<?php

namespace App\Application\Actions\Product\Elasticsearch\DTO;

use App\Entity\Product;

class UpdateElasticsearchProductRequest
{
    private int $productId;

    private ?Product $product;

    private array $properties;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
        $this->product = null;
        $this->properties = [];
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function addProperty(array $propertyValue): self
    {
        $this->properties[] = $propertyValue;
        return $this;
    }
}
