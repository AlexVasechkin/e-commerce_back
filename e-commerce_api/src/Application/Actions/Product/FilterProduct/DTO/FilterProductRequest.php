<?php

namespace App\Application\Actions\Product\FilterProduct\DTO;

class FilterProductRequest
{
    private ?int $categoryId = null;

    private ?string $name = null;

    private ?int $vendorId = null;

    private array $properties = [];

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int|null $categoryId
     */
    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVendorId(): ?int
    {
        return $this->vendorId;
    }

    /**
     * @param int|null $vendorId
     */
    public function setVendorId(?int $vendorId): self
    {
        $this->vendorId = $vendorId;
        return $this;
    }

    public function addProperty(string $key, array $values): self
    {
        $this->properties["$key"] = $values;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
