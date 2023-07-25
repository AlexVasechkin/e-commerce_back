<?php

namespace App\Application\Services\Parsing\DTO;

use App\Application\Services\Parsing\Contracts\GetWebpageContentInterface;

class WebpageContent
    implements GetWebpageContentInterface
{
    private string $webpageContent;

    public function __construct(string $webpageContent)
    {
        $this->webpageContent = $webpageContent;
    }

    public function getWebpageContent(): string
    {
        return $this->webpageContent;
    }
}
