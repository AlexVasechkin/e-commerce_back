<?php

namespace App\Application\Actions\Product;

use App\Entity\Contracts\PutProductInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;

class CreateProductAction
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function execute(PutProductInterface $request): Product
    {
        try {
            return $this->productRepository->findOneByCodeAndVendorOrFail(
                $request->getCode(),
                $request->getVendor()
            );
        } catch (\Throwable $e) {
            return $this->productRepository->save(
                (new Product())->put($request)
            );
        }
    }
}
