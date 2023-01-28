<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\Product\Elasticsearch\CreateElasticsearchProductAction;
use App\Application\Actions\Product\Elasticsearch\DTO\UpdateElasticsearchProductRequest;
use App\Application\Actions\Product\Elasticsearch\UpdateElasticsearchProductAction;
use App\Entity\Product;
use App\Entity\Vendor;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Avn\Paginator\Paginator;
use Avn\Paginator\PaginatorRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
            'price' => $product->getPrice() ?? 0
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
        CreateElasticsearchProductAction $createElasticsearchProductAction
    ) {
        $payload = $httpRequest->toArray();

        $product = $productRepository->create(
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

        $createElasticsearchProductAction->execute($product);

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
        VendorRepository $vendorRepository,
        UpdateElasticsearchProductAction $updateElasticsearchProductAction
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

        $productRepository->update($product);

        $updateElasticsearchProductAction->execute(
            (new UpdateElasticsearchProductRequest($product->getId()))
                ->setProduct($product)
        );

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/api/v1/private/products", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list(
        ProductRepository $productRepository
    ) {
        $idList = $productRepository->getFullIdList();

        $pagination = (new Paginator())->paginate(
            (new PaginatorRequest($idList))
                ->setCurrentPage(1)
                ->setLimit(10)
        );

        return $this->json([
            'payload' => array_map(function (Product $product) {
                return [
                    'id' => $product->getId(),
                    'code' => $product->getCode(),
                    'name' => $product->getName(),
                    'count' => $product->getCount()
                ];
            }, $productRepository->findById($pagination->getIdList())),
            'totalPageCount' => $pagination->getTotalPageCount()
        ]);
    }

    /**
     * @Route("/api/v1/public/products", methods={"POST"})
     */
    public function getProducts(
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository,
        VendorRepository $vendorRepository,
        ParameterBagInterface $parameterBag
    ) {
        $images = [];
        foreach ($productImageRepository->findAll() as $image) {
            if (!$image->getIsDeleted()) {
                $images[$image->getProduct()->getId()][] = [
                    'id' => $image->getId()->toBase32(),
                    'path' => $parameterBag->get('app.images_host') . '/product-image/' . $image->getId()->toBase32(),
                    'description' => $image->getDescription() ?? '',
                    'sortOrder' => $image->getSortOrder()
                ];
            }
        }

        $vendors = [];
        foreach ($vendorRepository->findAll() as $vendor) {
            $vendors[$vendor->getId()] = [
                'id' => $vendor->getId(),
                'name' => $vendor->getName()
            ];
        }

        return $this->json([
            'payload' => array_map(function (Product $product) use ($images, $vendors) {
                return [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice() ?? 0,
                    'images' => $images[$product->getId()] ?? [],
                    'vendor' => $vendors[$product->getVendor()->getId()] ?? [],
                ];
            }, $productRepository->fetchProductsWithPages())
        ]);
    }
}
