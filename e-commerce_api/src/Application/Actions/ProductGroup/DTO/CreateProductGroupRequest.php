<?php

namespace App\Application\Actions\ProductGroup\DTO;

use App\Entity\Contracts\PutProductGroupInterface;

class CreateProductGroupRequest
    implements PutProductGroupInterface
{
    private string $name;

    private bool $isToHomepage = false;

    private int $homepageSort = 500;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function isToHomepage(): bool
    {
        return $this->isToHomepage;
    }

    public function setIsToHomepage(bool $isToHomepage): self
    {
        $this->isToHomepage = $isToHomepage;
        return $this;
    }

    public function getHomepageSort(): int
    {
        return $this->homepageSort;
    }

    public function setHomepageSort(int $homepageSort): self
    {
        $this->homepageSort = $homepageSort;
        return $this;
    }
}
