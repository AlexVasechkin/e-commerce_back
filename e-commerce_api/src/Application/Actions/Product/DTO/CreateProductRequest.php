<?php

namespace App\Application\Actions\Product\DTO;

use App\Entity\Contracts\PutProductInterface;
use App\Entity\Vendor;

class CreateProductRequest
    implements PutProductInterface
{
    private string $code;

    private Vendor $vendor;

    private string $name;

    private int $price;

    private int $count;

    private float $width;

    private float $height;

    private float $length;

    private float $mass;

    private ?string $donorUrl = null;

    private ?string $parserCode = null;

    public function __construct(
        string $code,
        Vendor $vendor,
        string $name,
        int $price,
        int $count,
        float $width,
        float $height,
        float $length,
        float $mass
    ) {
        $this->code = $code;
        $this->vendor = $vendor;
        $this->name = $name;
        $this->price = $price;
        $this->count = $count;
        $this->width = $width;
        $this->height = $height;
        $this->length = $length;
        $this->mass = $mass;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return Vendor
     */
    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @return float
     */
    public function getMass(): float
    {
        return $this->mass;
    }

    public function getDonorUrl(): ?string
    {
        return $this->donorUrl;
    }

    public function setDonorUrl(?string $donorUrl): self
    {
        $this->donorUrl = $donorUrl;
        return $this;
    }

    public function getParserCode(): ?string
    {
        return $this->parserCode;
    }

    public function setParserCode(?string $parserCode): self
    {
        $this->parserCode = $parserCode;
        return $this;
    }
}
