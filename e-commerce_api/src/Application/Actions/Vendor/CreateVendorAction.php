<?php

namespace App\Application\Actions\Vendor;

use App\Entity\Contracts\PutVendorInterface;
use App\Entity\Vendor;
use App\Repository\VendorRepository;

class CreateVendorAction
{
    private VendorRepository $vendorRepository;

    /**
     * @param VendorRepository $vendorRepository
     */
    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    public function execute(PutVendorInterface $request): Vendor
    {
        try {
            return $this->vendorRepository->findOneByNameOrFail($request->getName());
        } catch (\Throwable $e) {
            return $this->vendorRepository->save(
                (new Vendor())->put($request),
                true
            );
        }
    }
}
