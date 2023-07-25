<?php

namespace App\Application\Actions\Product;

use App\Application\Actions\Product\DTO\CreateProductRequest;
use App\Application\Actions\Product\DTO\ImportProductsRequest;
use App\Application\Actions\ProductCategoryItem\CreateProductCategoryItemAction;
use App\Application\Actions\ProductCategoryItem\DTO\CreateProductCategoryItemRequest;
use App\Application\Actions\Vendor\CreateVendorAction;
use App\Application\Actions\Vendor\DTO\CreateVendorRequest;
use App\Repository\ProductCategoryRepository;
use App\Repository\VendorRepository;

class ImportProductsAction
{
    private const COLUMN_CODE = 0;
    private const COLUMN_VENDOR = 1;
    private const COLUMN_NAME = 2;
    private const COLUMN_DONOR_URL = 3;
    private const COLUMN_PARSER_CODE = 4;

    private const PARSER_CODE_DEFAULT = 'worldguns';

    private CreateVendorAction $createVendorAction;

    private VendorRepository $vendorRepository;

    private CreateProductAction $createProductAction;

    private CreateProductCategoryItemAction $createProductCategoryItemAction;

    private ProductCategoryRepository $productCategoryRepository;

    public function __construct(
        CreateVendorAction $createVendorAction,
        VendorRepository $vendorRepository,
        CreateProductAction $createProductAction,
        CreateProductCategoryItemAction $createProductCategoryItemAction,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $this->createVendorAction = $createVendorAction;
        $this->vendorRepository = $vendorRepository;
        $this->createProductAction = $createProductAction;
        $this->createProductCategoryItemAction = $createProductCategoryItemAction;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function importVendors(array $vendorList): void
    {
        $existsList = array_column($this->vendorRepository->fetchDataForDict(), 'name');

        $toAddList = array_diff($vendorList, $existsList);

        foreach ($toAddList as $vendorName) {
            $this->createVendorAction->execute(
                new CreateVendorRequest($vendorName)
            );
        }
    }

    public function reloadVendorDict(): array
    {
        $result = [];

        foreach ($this->vendorRepository->findAll() as $vendor) {
            $result[$vendor->getName()] = $vendor;
        }

        return $result;
    }

    public function execute(ImportProductsRequest $request): array
    {
        $this->importVendors(array_unique(array_column($request->getDataSet(), self::COLUMN_VENDOR)));

        $vendorDict = $this->reloadVendorDict();

        is_null($request->getProductCategory())
            ? $request->setProductCategory($this->productCategoryRepository->findOneBy(['name' => 'товары']))
            : null;

        $response = [
            'errors' => []
        ];

        foreach ($request->getDataSet() as $productData) {
            try  {
                if (   (   isset($productData[self::COLUMN_CODE])
                        && is_string($productData[self::COLUMN_CODE])
                       )
                    && (   isset($productData[self::COLUMN_VENDOR])
                        && is_string($productData[self::COLUMN_VENDOR])
                        && isset($vendorDict[trim($productData[self::COLUMN_VENDOR])])
                       )
                ) {
                    $product = $this->createProductAction->execute(
                        (new CreateProductRequest(
                            trim($productData[self::COLUMN_CODE]),
                            $vendorDict[trim($productData[self::COLUMN_VENDOR])],
                            trim($productData[self::COLUMN_NAME] ?? ''),
                            0,
                            0,
                            0,
                            0,
                            0,
                            0
                            )
                        )
                            ->setDonorUrl($productData[self::COLUMN_DONOR_URL] ?? null)
                            ->setParserCode($productData[self::COLUMN_PARSER_CODE] ?? self::PARSER_CODE_DEFAULT)
                    );

                    if ($request->getProductCategory()) {
                        $this->createProductCategoryItemAction->execute(
                            new CreateProductCategoryItemRequest($product, $request->getProductCategory())
                        );
                    }
                }
            } catch (\Throwable $e) {
                $response['errors'][] = $e->getMessage();
            }
        }

        return $response;
    }
}
