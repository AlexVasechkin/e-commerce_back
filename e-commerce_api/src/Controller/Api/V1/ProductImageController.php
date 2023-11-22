<?php

namespace App\Controller\Api\V1;

use App\Application\Services\ObjectStorage\ObjectStorageAdapter;
use App\Entity\ProductImage;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductImageController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-image/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductImageRepository $productImageRepository,
        ProductRepository $productRepository,
        ObjectStorageAdapter $objectStorageAdapter,
        ParameterBagInterface $parameterBag
    ) {
        $image = (new ProductImage())
            ->setProduct($productRepository->findOneBy(['id' => $httpRequest->request->get('productId')]))
            ->setPath('')
        ;

        $productImageRepository->save($image, true);

        /** @var UploadedFile $file */
        $file = $httpRequest->files->get('image');

        $resource = \fopen($file->getRealPath(), 'r');
        $fileName = $image->getId() . '.' . $file->getClientOriginalExtension();
        $objectStorageAdapter->client->PutObject([
            'Bucket' => $parameterBag->get('app.object_storage.bucket'),
            'Key' => $fileName,
            'Body' => $resource,
        ]);

        $image->setPath(sprintf('/%s/', $parameterBag->get('app.object_storage.bucket')) . $fileName);
        $productImageRepository->save($image, true);

        return $this->json([
            'id' => $image->getId()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-images/{id}", methods={"GET"})
     */
    public function list(
        $id,
        ProductImageRepository $productImageRepository,
        ProductRepository $productRepository
    ) {
        return $this->json([
            'payload' => array_map(function (ProductImage $image) {
                return [
                    'id' => $image->getId(),
                    'path' => '/product-image/' . $image->getId(),
                    'sortOrder' => $image->getSortOrder(),
                    'description' => $image->getDescription(),
                    'isDeleted' => $image->getIsDeleted()
                ];
            }, $productImageRepository->findBy(['product' => $productRepository->findOneBy(['id' => $id])]))
        ]);
    }

    /**
     * @Route("/product-image/{id}", methods={"GET"})
     */
    public function getImage(
        $id,
        ObjectStorageAdapter $objectStorageAdapter,
        ProductImageRepository $productImageRepository,
        ParameterBagInterface $parameterBag
    ) {
        $fileDir = fn() => $parameterBag->get('kernel.cache_dir') . '/product-images';
        if (!file_exists($fileDir())) {
            mkdir($fileDir(), 0755, true);
        }

        $productImage = $productImageRepository->findOneBy(['id' => $id]);

        if (is_null($productImage)) {
            throw new NotFoundHttpException();
        }

        $filePath = $productImage->getPath();
        $fileName = str_replace(sprintf('/%s/', $parameterBag->get('app.object_storage.bucket')), '', $filePath);

        $filePath = $fileDir() . '/' . $fileName;

        if (file_exists($filePath)) {
            return new BinaryFileResponse($filePath);
        }

        $response = $objectStorageAdapter->client->getObject([
            'Bucket' => $parameterBag->get('app.object_storage.bucket'),
            'Key' => $fileName
        ]);
        stream_copy_to_stream($response->getBody()->getContentAsResource(), fopen($filePath, 'wb'));

        return new BinaryFileResponse($filePath);
    }

    /**
     * @Route("/api/v1/private/product-image/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        ProductImageRepository $productImageRepository
    ) {
        $requestParams = $httpRequest->toArray();

        $image = $productImageRepository->findOneBy(['id' => $requestParams['id']]);

        if (is_null($image)) {
            throw new NotFoundHttpException('Image not found.');
        }

        isset($requestParams['description']) ? $image->setDescription($requestParams['description']) : null;

        isset($requestParams['sortOrder']) ? $image->setSortOrder($requestParams['sortOrder']) : null;

        isset($requestParams['isDeleted']) ? $image->setIsDeleted($requestParams['isDeleted']) : null;

        $productImageRepository->save($image, true);

        return $this->json([]);
    }
}
