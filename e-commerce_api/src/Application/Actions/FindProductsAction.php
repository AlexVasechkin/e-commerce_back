<?php

namespace App\Application\Actions;

use App\Application\Actions\Product\FetchProductsAction;
use App\Application\Actions\Product\FilterProductsAction;

class FindProductsAction
{
    private FilterProductsAction $filterProductsAction;

    private FetchProductsAction $fetchProductsAction;

    public function __construct(
        FilterProductsAction $filterProductsAction,
        FetchProductsAction $fetchProductsAction
    ) {
        $this->filterProductsAction = $filterProductsAction;
        $this->fetchProductsAction = $fetchProductsAction;
    }

    public function execute(array $filters): array
    {
        $idList = $this->filterProductsAction->execute($filters);
        return $this->fetchProductsAction->execute($idList);
    }
}
