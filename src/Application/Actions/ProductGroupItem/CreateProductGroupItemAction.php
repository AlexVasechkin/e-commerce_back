<?php

namespace App\Application\Actions\ProductGroupItem;

use App\Entity\Contracts\PutProductGroupItemInterface;
use App\Entity\ProductGroupItem;
use App\Repository\ProductGroupItemRepository;
use Throwable;

class CreateProductGroupItemAction
{
    private ProductGroupItemRepository $productGroupItemRepository;

    public function __construct(ProductGroupItemRepository $productGroupItemRepository)
    {
        $this->productGroupItemRepository = $productGroupItemRepository;
    }

    public function execute(PutProductGroupItemInterface $request): ProductGroupItem
    {
        try {
            return $this->productGroupItemRepository->findOneByProductAndGroupOrFail($request->getProduct(), $request->getProductGroup());
        } catch (Throwable $e) {
            return $this->productGroupItemRepository->save((new ProductGroupItem())->put($request), true);
        }
    }
}
