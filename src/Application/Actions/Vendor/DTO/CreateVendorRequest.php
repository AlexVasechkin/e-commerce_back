<?php

namespace App\Application\Actions\Vendor\DTO;

use App\Entity\Contracts\PutVendorInterface;

class CreateVendorRequest
    implements PutVendorInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
