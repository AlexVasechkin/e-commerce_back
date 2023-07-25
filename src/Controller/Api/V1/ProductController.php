<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\Product\DTO\ImportProductsRequest;
use App\Application\Actions\Product\FetchProductsAction;
use App\Application\Actions\Product\FilterProductsAction;
use App\Application\Actions\Product\ImportProductsAction;
use App\Entity\Product;
use App\Entity\ProductCategoryItem;
use App\Entity\ProductGroup;
use App\Entity\ProductGroupCategoryItem;
use App\Repository\ProductCategoryItemRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductGroupCategoryItemRepository;
use App\Repository\ProductGroupRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Avn\Paginator\Paginator;
use Avn\Paginator\PaginatorRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private function serialize(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'code' => $product->getCode(),
            'vendorId' => $product->getVendor()->getId(),
            'name' => $product->getName(),
            'length' => $product->getLength(),
            'width' => $product->getWidth(),
            'height' => $product->getHeight(),
            'mass' => $product->getMass(),
            'count' => $product->getCount(),
            'price' => $product->getPrice() ?? 0,
            'donorUrl' => $product->getDonorUrl(),
            'parserCode' => $product->getParserCode()
        ];
    }

    /**
     * @Route("/api/v1/private/product/{id}", methods={"GET"})
     */
    public function getInstance(
        $id,
        ProductRepository $productRepository
    ) {
        $product = $productRepository->findOneBy(['id' => intval($id)]);

        return $this->json([
            'payload' => $product ? $this->serialize($product) : null
        ]);
    }

    /**
     * @Route("/api/v1/private/product/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductRepository $productRepository,
        VendorRepository $vendorRepository,
        ProductCategoryItemRepository $productCategoryItemRepository,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $payload = $httpRequest->toArray();

        $product = $productRepository->save(
            (new Product())
                ->setCode($payload['code'])
                ->setVendor($vendorRepository->findOneBy(['id' => $payload['vendorId']]))
                ->setName('')
                ->setLength(0)
                ->setWidth(0)
                ->setHeight(0)
                ->setMass(0)
                ->setCount(0)
                ->setPrice(0)
        );

        $productCategoryId = $payload['productCategoryId'] ?? null;

        if ($productCategoryId) {
            $productCategory = $productCategoryRepository->findOneBy(['id' => $productCategoryId]);
        } else {
            $productCategory = $productCategoryRepository->findOneBy(['name' => 'товары']);
        }

        $productCategoryItemRepository->save(
            (new ProductCategoryItem())
                ->setProduct($product)
                ->setCategory($productCategory)
        );

        $productRepository->save($product);

        return $this->json([
            'id' => $product->getId()
        ]);
    }

    /**
     * @Route("/api/v1/private/product/update", methods={"POST"})
     * @param Request $httpRequest
     * @param ProductRepository $productRepository
     * @param VendorRepository $vendorRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(
        Request $httpRequest,
        ProductRepository $productRepository,
        VendorRepository $vendorRepository
    ) {
        $payload = $httpRequest->toArray();

        $id = intval($payload['id']);

        $product = $productRepository->findOneBy(['id' => $id]);

        if (is_null($product)) {
            throw new NotFoundHttpException();
        }

        isset($payload['code']) ? $product->setCode($payload['code']) : null;

        if (isset($payload['vendorId'])) {
            $product->setVendor($vendorRepository->findOneBy(['id' => $payload['vendorId']]));
        }

        isset($payload['name']) ? $product->setName($payload['name']) : null;

        isset($payload['length']) ? $product->setLength(floatval($payload['length'])) : null;

        isset($payload['width']) ? $product->setWidth(floatval($payload['width'])) : null;

        isset($payload['height']) ? $product->setHeight(floatval($payload['height'])) : null;

        isset($payload['mass']) ? $product->setMass(floatval($payload['mass'])) : null;

        isset($payload['count']) ? $product->setCount($payload['count']) : null;

        (isset($payload['price']) && is_int($payload['price'])) ? $product->setPrice($payload['price']) : null;

        !empty($payload['parserCode']) ? $product->setParserCode($payload['parserCode']) : null;

        !empty($payload['donorUrl']) ? $product->setDonorUrl($payload['donorUrl']) : null;

        $productRepository->update($product);

        return $this->json(['success' => true]);
    }

    private function listProduct(
        array $rp,
        FilterProductsAction $filterProductsAction,
        FetchProductsAction $fetchProductsAction
    ): array
    {
        $currentPage = $rp['currentPage'] ?? 1;
        (is_int($currentPage) && ($currentPage > 0)) || $currentPage = 1;

        $limit = $rp['limit'] ?? 12;
        (is_int($limit) && ($limit > 0)) || $limit = 12;

        $idList = $filterProductsAction->execute($rp['filters'] ?? []);

        $pagination = (new Paginator())->paginate(
            (new PaginatorRequest($idList))
                ->setCurrentPage($currentPage)
                ->setLimit($limit)
        );

        return [
            'payload' => $fetchProductsAction->execute($pagination->getIdList()),
            'currentPage' => $currentPage,
            'totalPageCount' => $pagination->getTotalPageCount(),
            'limit' => $limit
        ];
    }

    /**
     * @Route("/api/v1/public/products", methods={"POST"})
     */
    public function getProducts(
        Request $httpRequest,
        FilterProductsAction $filterProductsAction,
        FetchProductsAction $fetchProductsAction
    ) {
        return $this->json(
            $this->listProduct(
                $httpRequest->toArray(),
                $filterProductsAction,
                $fetchProductsAction
            )
        );
    }

    /**
     * @Route("/api/v1/private/products", methods={"POST"})
     */
    public function list(
        Request $httpRequest,
        FilterProductsAction $filterProductsAction,
        FetchProductsAction $fetchProductsAction
    ) {
        return $this->json(
            $this->listProduct(
                $httpRequest->toArray(),
                $filterProductsAction,
                $fetchProductsAction
            )
        );
    }

    /**
     * @Route("/api/v1/private/import-products", methods={"POST"}, name="app_cp_import-products")
     */
    public function importProducts(
        Request $httpRequest,
        ImportProductsAction $importProductsAction,
        ProductCategoryRepository $productCategoryRepository
    ) {
        /** @var UploadedFile $file */
        $file = $httpRequest->files->get('file');

        $productCategoryId = $httpRequest->get('categoryId');

        $request = (new ImportProductsRequest())
            ->setDataSet(array_map(fn(string $line) => str_getcsv($line, ';'), explode("\r\n", $file->getContent())));

        if ($productCategoryId) {
            $request->setProductCategory($productCategoryRepository->findOneBy(['id' => $productCategoryId]));
        }

        $response = $importProductsAction->execute($request);

        return $this->json($response);
    }

    /**
     * @Route("/api/v1/public/category-products-by-groups/{id}", {"GET"})
     */
    public function listProductsViaGroupsForCategory(
        $id,
        ProductGroupCategoryItemRepository $productGroupCategoryItemRepository,
        ProductGroupRepository $productGroupRepository,
        FilterProductsAction $filterProductsAction,
        FetchProductsAction $fetchProductsAction
    ) {
        $categoryId = intval($id);

        $productGroupCategoryItems = $productGroupCategoryItemRepository->fetchGroupsByCategories([$categoryId]);
        $productGroupIdList = array_map(fn(ProductGroupCategoryItem $item) => $item->getProductGroup()->getId(), $productGroupCategoryItems);

        $productGroupsDict = [];
        foreach ($productGroupRepository->fetchModelsById($productGroupIdList) as $productGroup) {
            $productGroupsDict[$productGroup->getId()] = $productGroup;
        }

        return $this->json([
            'payload' => [
                'groups' => array_map(function (ProductGroupCategoryItem $item) use ($productGroupsDict) {

                    /** @var ProductGroup $productGroup */
                    $productGroup = $productGroupsDict[$item->getProductGroup()->getId()] ?? null;

                    return [
                        'id' => $productGroup ? $productGroup->getId() : null,
                        'name' => $productGroup ? $productGroup->getName() : null,
                        'sortOrder' => $item->getSortOrder()
                    ];

                }, $productGroupCategoryItems),
                'products' => $fetchProductsAction->execute(
                    $filterProductsAction->execute([
                        'categoryId' => $categoryId,
                        'productPageActive' => true,
                        'productGroupIdList' => $productGroupIdList
                    ])
                )
            ]
        ]);
    }
}
