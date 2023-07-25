<?php

namespace App\Application\Actions\ProductWebpage;

use App\Application\Actions\ProductWebpage\DTO\CreateProductWebpageRequest;
use App\Application\Actions\Webpage\CreateWebpageAction;
use App\Application\Actions\Webpage\DTO\CreateWebpageRequest;
use App\Entity\Product;
use App\Entity\ProductWebpage;
use Behat\Transliterator\Transliterator;

class CreateProductWebpageFromProductAction
{
    private CreateProductWebpageAction $createProductWebpageAction;

    private CreateWebpageAction $createWebpageAction;

    public function __construct(CreateProductWebpageAction $createProductWebpageAction, CreateWebpageAction $createWebpageAction)
    {
        $this->createProductWebpageAction = $createProductWebpageAction;
        $this->createWebpageAction = $createWebpageAction;
    }

    public function execute(Product $product): ProductWebpage
    {
        $vendor = $product->getVendor();

        $productCategory = $product->getProductCategoryItems()->first()->getCategory();

        $alias = mb_strtolower(trim(implode('-', [
            $productCategory ? ($productCategory->getNameSingle() ?? '') : '',
            $vendor ? ($vendor->getName() ?? '') : '',
            $product->getName()
        ])), 'UTF-8');

        $webpage = $this->createWebpageAction->execute(
            (new CreateWebpageRequest($product->getName(), Transliterator::transliterate($alias)))
                ->setPagetitle($product->getName())
                ->setHeadline($product->getName())
        );

        return $this->createProductWebpageAction->execute(
            (new CreateProductWebpageRequest($product, $webpage))
        );
    }
}
