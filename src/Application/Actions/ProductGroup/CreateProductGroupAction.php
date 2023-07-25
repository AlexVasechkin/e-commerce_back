<?php

namespace App\Application\Actions\ProductGroup;

use App\Entity\Contracts\PutProductGroupInterface;
use App\Entity\ProductGroup;
use App\Repository\ProductGroupRepository;
use Throwable;

class CreateProductGroupAction
{
    private ProductGroupRepository $productGroupRepository;

    public function __construct(ProductGroupRepository $productGroupRepository)
    {
        $this->productGroupRepository = $productGroupRepository;
    }

    public function execute(PutProductGroupInterface $request): ProductGroup
    {
        try {
            return $this->productGroupRepository->findOneByNameOrFail($request->getName());
        } catch (Throwable $e) {
            return $this->productGroupRepository->save((new ProductGroup())->put($request), true);
        }
    }
}
