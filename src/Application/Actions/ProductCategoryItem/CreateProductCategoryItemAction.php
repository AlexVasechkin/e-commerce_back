<?php

namespace App\Application\Actions\ProductCategoryItem;

use App\Entity\Contracts\PutProductCategoryItemInterface;
use App\Entity\ProductCategoryItem;
use App\Repository\ProductCategoryItemRepository;

class CreateProductCategoryItemAction
{
    private ProductCategoryItemRepository $productCategoryItemRepository;

    public function __construct(ProductCategoryItemRepository $productCategoryItemRepository)
    {
        $this->productCategoryItemRepository = $productCategoryItemRepository;
    }

    public function execute(PutProductCategoryItemInterface $request): ProductCategoryItem
    {
        try {
            return $this->productCategoryItemRepository->findOneByCategoryAndProductOrFail($request->getProduct(), $request->getProductCategory());
        } catch (\Throwable $e) {
            return $this->productCategoryItemRepository->save(
                (new ProductCategoryItem())->put($request),
                true
            );
        }
    }
}
