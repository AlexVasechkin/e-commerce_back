<?php

namespace App\Application\Actions\ProductWebpage;

use App\Entity\Contracts\PutProductWebpageInterface;
use App\Entity\ProductWebpage;
use App\Repository\ProductWebpageRepository;

class CreateProductWebpageAction
{
    private ProductWebpageRepository $productWebpageRepository;

    public function __construct(ProductWebpageRepository $productWebpageRepository)
    {
        $this->productWebpageRepository = $productWebpageRepository;
    }

    public function execute(PutProductWebpageInterface $request): ProductWebpage
    {
        try {
            return $this->productWebpageRepository->findOneByProductAndWebpageOrFail($request->getProduct(), $request->getWebpage());
        } catch (\Throwable $e) {
            return $this->productWebpageRepository->save((new ProductWebpage())->put($request), true);
        }
    }
}
