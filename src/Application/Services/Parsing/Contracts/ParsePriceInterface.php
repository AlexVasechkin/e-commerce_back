<?php

namespace App\Application\Services\Parsing\Contracts;

interface ParsePriceInterface
{
    public function parsePrice(GetWebpageContentInterface $webpageContentDto): ?string;
}
