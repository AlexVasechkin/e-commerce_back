<?php

namespace App\Application\Actions\ProductWebpage\DTO;

use App\Entity\Contracts\PutProductWebpageInterface;
use App\Entity\Product;
use App\Entity\Webpage;

class CreateProductWebpageRequest
    implements PutProductWebpageInterface
{
    private Product $product;

    private Webpage $webpage;

    public function __construct(
        Product $product,
        Webpage $webpage
    ) {
        $this->product = $product;
        $this->webpage = $webpage;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getWebpage(): Webpage
    {
        return $this->webpage;
    }
}
