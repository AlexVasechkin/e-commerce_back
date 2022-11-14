<?php

namespace App\Domain\Contracts;

interface ProductInterface extends Base\GetIntIdInterface,
                                   Base\GetSetCodeInterface,
                                   Base\GetSetNameInterface
{
    public function getVendor(): ?VendorInterface;

    public function setVendor(VendorInterface $vendor): self;

    public function getLength(): ?float;

    public function setLength(float $length): self;

    public function getWidth(): ?float;

    public function setWidth(float $width): self;

    public function getHeight(): ?float;

    public function setHeight(float $height): self;

    public function getMass(): ?float;

    public function setMass(float $mass): self;
}
