<?php

namespace App\Application\Actions\Product;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\ProductCategoryItem;
use App\Entity\ProductGroup;
use App\Entity\ProductGroupItem;
use App\Entity\Vendor;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductGroupItemRepository;
use App\Repository\ProductGroupRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FetchProductsAction
{
    private ProductImageRepository $productImageRepository;

    private ParameterBagInterface $parameterBag;

    private VendorRepository $vendorRepository;

    private ProductCategoryRepository $productCategoryRepository;

    private ProductRepository $productRepository;

    private ProductGroupItemRepository $productGroupItemRepository;

    private ProductGroupRepository $productGroupRepository;

    /**
     * @param ProductImageRepository $productImageRepository
     * @param ParameterBagInterface $parameterBag
     * @param VendorRepository $vendorRepository
     * @param ProductCategoryRepository $productCategoryRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductImageRepository $productImageRepository,
        ParameterBagInterface $parameterBag,
        VendorRepository $vendorRepository,
        ProductCategoryRepository $productCategoryRepository,
        ProductRepository $productRepository,
        ProductGroupItemRepository $productGroupItemRepository,
        ProductGroupRepository $productGroupRepository
    ) {
        $this->productImageRepository = $productImageRepository;
        $this->parameterBag = $parameterBag;
        $this->vendorRepository = $vendorRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productRepository = $productRepository;
        $this->productGroupItemRepository = $productGroupItemRepository;
        $this->productGroupRepository = $productGroupRepository;
    }

    public function execute(array $idList): array
    {
        $images = [];
        foreach ($this->productImageRepository->findAll() as $image) {
            if (!$image->getIsDeleted()) {
                $images[$image->getProduct()->getId()][] = [
                    'id' => $image->getId()->toBase32(),
                    'path' => $this->parameterBag->get('app.images_host') . '/product-image/' . $image->getId()->toBase32(),
                    'description' => $image->getDescription() ?? '',
                    'sortOrder' => $image->getSortOrder()
                ];
            }
        }

        $vendors = [];
        foreach ($this->vendorRepository->findAll() as $vendor) {
            $vendors[$vendor->getId()] = [
                'id' => $vendor->getId(),
                'name' => $vendor->getName()
            ];
        }

        $productCategories = [];
        foreach ($this->productCategoryRepository->findAll() as $productCategory) {
            $productCategories[$productCategory->getId()] = [
                'id' => $productCategory->getId(),
                'nameSingle' => $productCategory->getNameSingle()
            ];
        }

        $productGroupItems = $this->productGroupItemRepository->fetchModelsByProducts($idList);
        $productGroups = $this->productGroupRepository->fetchModelsById(
            array_unique(array_map(fn(ProductGroupItem $item) => $item->getProductGroup()->getId(), $productGroupItems))
        );

        return array_map(function (Product $product) use ($images, $vendors, $productCategories, $productGroupItems, $productGroups) {

            $pci = $product->getProductCategoryItems()->first();

            $productGroupIdList = array_map(fn(ProductGroupItem $item) => $item->getProductGroup()->getId(), array_filter($productGroupItems, function (ProductGroupItem $item) use ($product) {
                return $item->getProduct()->getId() === $product->getId();
            }));

            $targetGroups = array_filter($productGroups, function (ProductGroup $productGroup) use ($productGroupIdList) {
                return in_array($productGroup->getId(), $productGroupIdList);
            });

            return [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'price' => $product->getPrice() ?? 0,
                'count' => $product->getCount() ?? 0,
                'images' => $images[$product->getId()] ?? [],
                'vendor' => ($product->getVendor() instanceof Vendor)
                    ? $vendors[$product->getVendor()->getId()]
                    : [
                        'id' => null,
                        'name' => null
                    ],
                'productCategory' => (($pci instanceof ProductCategoryItem) && ($pci->getCategory() instanceof ProductCategory))
                    ? $productCategories[$pci->getCategory()->getId()]
                    : [
                        'id' => null,
                        'nameSingle' => null
                    ],
                'productGroups' => array_values(array_map(function (ProductGroup $productGroup) {
                    return [
                        'id' => $productGroup->getId(),
                        'name' => $productGroup->getName()
                    ];
                }, $targetGroups))
            ];
        }, $this->productRepository->findBy(['id' => $idList]));
    }
}
