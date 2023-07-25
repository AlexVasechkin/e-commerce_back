<?php

namespace App\Entity\Contracts;

use App\Entity\Product;
use App\Entity\Webpage;

interface PutProductWebpageInterface
{
    public function getProduct(): Product;

    public function getWebpage(): Webpage;
}
