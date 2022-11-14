<?php

namespace App\Controller\Api\V1;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Avn\Paginator\Paginator;
use Avn\Paginator\PaginatorRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product/{id}", methods={"GET"})
     */
    public function getInstance(
        $id,
        ProductRepository $productRepository
    ) {
        $product = $productRepository->findOneBy(['id' => intval($id)]);

        if (is_null($product)) {
            throw new NotFoundHttpException();
        }

        return $this->json([
            'payload' => [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'vendorId' => $product->getVendor()->getId(),
                'name' => $product->getName(),
                'length' => $product->getLength(),
                'width' => $product->getWidth(),
                'height' => $product->getHeight(),
                'mass' => $product->getMass()
            ]
        ], 200, ['Access-Control-Allow-Origin' => '*']);
    }

    /**
     * @Route("/api/v1/private/product/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductRepository $productRepository,
        VendorRepository $vendorRepository
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
        );

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

        $productRepository->update($product);

        return $this->json(['success' => true], 200, ['Access-Control-Allow-Origin' => '*']);
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

        return $this->json(
            [
                'payload' => array_map(function (Product $product) {
                    return [
                        'id' => $product->getId(),
                        'code' => $product->getCode(),
                        'name' => $product->getName()
                    ];
                }, $productRepository->findById($pagination->getIdList())),
                'totalPageCount' => $pagination->getTotalPageCount()
            ]
        , 200, ['Access-Control-Allow-Origin' => '*']);
    }
}
