<?php

namespace App\Entity\Contracts;

use App\Entity\Vendor;

interface PutProductInterface
{
    public function getCode(): string;

    public function getVendor(): Vendor;

    public function getName(): string;

    public function getPrice(): int;

    public function getCount(): int;

    public function getWidth(): float;

    public function getHeight(): float;

    public function getLength(): float;

    public function getMass(): float;

    public function getDonorUrl(): ?string;

    public function getParserCode(): ?string;
}
