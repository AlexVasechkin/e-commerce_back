<?php

namespace App\Controller\Api\V1;

use App\Application\Services\ObjectStorage\ObjectStorageAdapter;
use App\Entity\ProductCategory;
use App\Repository\CategoryWebpageRepository;
use App\Repository\ProductCategoryRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class ProductCategoryController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-category/{$id}", methods={"GET"})
     */
    public function getInstance($id, ProductCategoryRepository $productCategoryRepository)
    {
        $productCategory = $productCategoryRepository->findOneBy(['id' => $id]);
        if (is_null($productCategory)) {
            throw new NotFoundHttpException(sprintf('ProductCategory[id: %s] not found', $id));
        }

        return $this->json([
            'payload' => $this->serialize($productCategory)
        ]);
    }

    private function serialize(ProductCategory $entity): array
    {
        return [
            'id' => $entity->getId(),
            'parentId' => $entity->getParent() ? $entity->getParent()->getId() : null,
            'name' => $entity->getName(),
            'nameSingle' => $entity->getName(),
            'isActive' => $entity->isActive()
        ];
    }

    /**
     * @Route("/api/v1/private/product-category/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $requestParams = $httpRequest->toArray();

        $pc = (new ProductCategory())
            ->setName($requestParams['name'] ?? null)
            ->setNameSingle($requestParams['nameSingle'] ?? null)
            ->setIsActive(true)
        ;

        if (isset($requestParams['parentId'])) {
            $parentCategory = $productCategoryRepository->findOneBy(['id' => $requestParams['parentId']]);
            if ($parentCategory) {
                $pc->setParent($parentCategory);
            }
        }

        $productCategoryRepository->save($pc, true);

        return $this->json([
            'id' => $pc->getId()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category/list", methods={"GET"})
     */
    public function getList(
        Connection $connection,
        ParameterBagInterface $parameterBag
    ) {
        $query = implode(PHP_EOL, [
            'select',
            '     pc.id          as id',
            '    ,pc.name        as name',
            '    ,pc.name_single as name_single',
            '    ,pc.is_active   as is_active',
            '    ,pc.picture     as picture',
            '    ,p.id           as parent_id',
            '    ,p.name         as parent_name',
            '    ,cw.webpage_id  as webpage_id',
            '  from product_category as pc',
            '  left join product_category as p on p.id = pc.parent_id',
            '  left join category_webpage as cw on cw.category_id = pc.id',
            '  order by',
            '     pc.is_active desc',
            '    ,pc.name',
            '    ,p.name',
            ';'
        ]);

        return $this->json([
            'payload' => array_map(function (array $item) use ($parameterBag) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'nameSingle' => $item['name_single'],
                    'isActive' => $item['is_active'],
                    'parentId' => $item['parent_id'],
                    'parentName' => $item['parent_name'],
                    'webpageId' => $item['webpage_id'],
                    'picture' => $item['picture']
                        ? $parameterBag->get('app.images_host') . '/images/product-category-image/' . $item['id']
                        : null
                ];
            }, $connection->fetchAllAssociative($query))
        ]);
    }

    private function serializeForDict(ProductCategory $entity): array
    {
        return [
            'value' => $entity->getId(),
            'caption' => $entity->getName()
        ];
    }

    /**
     * @Route("/api/v1/private/product-category/dict", methods={"GET"})
     */
    public function getDict(
        ProductCategoryRepository $productCategoryRepository
    ) {
        return $this->json([
            'payload' => array_map(function (ProductCategory $pc) {
                return $this->serializeForDict($pc);
            }, $productCategoryRepository->findAll())
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $rp = $httpRequest->toArray();

        $id = $rp['id'] ?? null;

        $pc = $productCategoryRepository->findOneBy(['id' => $id]);

        if (is_null($pc)) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        isset($rp['name']) ? $pc->setName($rp['name']) : null;

        if (   isset($rp['nameSingle'])
            && is_string($rp['nameSingle'])
        ) {
            $pc->setNameSingle($rp['nameSingle']);
        }

        isset($rp['isActive']) ? $pc->setIsActive($rp['isActive']) : null;

        if (isset($rp['parentId'])) {
            $p = $productCategoryRepository->findOneBy(['id' => $rp['parentId']]);
            $pc->setParent($p);
        }

        $productCategoryRepository->save($pc, true);

        return $this->json(['payload' => $this->serialize($pc)]);
    }

    /**
     * @Route("/api/v1/public/product-category-pages", methods={"GET"})
     */
    public function getCategoryPages(
        CategoryWebpageRepository $categoryWebpageRepository,
        ParameterBagInterface $parameterBag
    ) {
        return $this->json([
            'payload' => array_map(function (array $row) use ($parameterBag) {
                return [
                    'categoryId' => $row['id'],
                    'name' => $row['name'],
                    'nameSingle' => $row['nameSingle'],
                    'picture' => $row['picture']
                        ? $parameterBag->get('app.images_host') . '/images/product-category-image/' . $row['id']
                        : null,
                    'alias' => $row['alias']
                ];
            }, $categoryWebpageRepository->fetchActiveWebpagesData())
        ]);
    }

    /**
     * @Route("/api/v1/private/product-category/upload-image", methods={"POST"})
     */
    public function uploadCategoryImage(
        Request $httpRequest,
        ProductCategoryRepository $productCategoryRepository,
        ObjectStorageAdapter $objectStorageAdapter,
        ParameterBagInterface $parameterBag
    ) {
        $productCategoryId = $httpRequest->request->get('categoryId');

        $productCategory = $productCategoryRepository->findOneBy(['id' => $productCategoryId]);

        /** @var UploadedFile $file */
        $file = $httpRequest->files->get('image');
        $resource = \fopen($file->getRealPath(), 'r');
        $fileName = Ulid::generate() . '.' . $file->getClientOriginalExtension();

        $productCategoryRepository->save(
            $productCategory->setPicture($fileName),
            true
        );

        $objectStorageAdapter->client->PutObject([
            'Bucket' => $parameterBag->get('app.object_storage.bucket'),
            'Key' => $fileName,
            'Body' => $resource,
        ]);

        return new Response();
    }

    /**
     * @Route("/images/product-category-image/{id}", methods={"GET"})
     */
    public function getImage(
        $id,
        ProductCategoryRepository $productCategoryRepository,
        ParameterBagInterface $parameterBag,
        ObjectStorageAdapter $objectStorageAdapter
    ) {
        $productCategory = $productCategoryRepository->findOneBy(['id' => $id]);

        if (   is_null($productCategory)
            || is_null($productCategory->getPicture())
        ) {
            throw new NotFoundHttpException();
        }

        $fileDir = fn() => $parameterBag->get('kernel.cache_dir') . '/product-category';
        if (!file_exists($fileDir())) {
            mkdir($fileDir(), 0755, true);
        }

        $filePath = $fileDir() . '/' . $productCategory->getPicture();

        if (file_exists($filePath)) {
            return new BinaryFileResponse($filePath);
        }

        $response = $objectStorageAdapter->client->getObject([
            'Bucket' => $parameterBag->get('app.object_storage.bucket'),
            'Key' => $productCategory->getPicture()
        ]);
        stream_copy_to_stream($response->getBody()->getContentAsResource(), fopen($filePath, 'wb'));

        return new BinaryFileResponse($filePath);
    }

    /**
     * @Route("/api/v1/public/product-category", methods={"POST"})
     */
    public function getCategoryPageData(
        Request $httpRequest,
        CategoryWebpageRepository $categoryWebpageRepository
    ) {
        $alias = $httpRequest->toArray()['alias'] ?? '';

        $categoryWebpage = $categoryWebpageRepository->findCategoryWebpageByAlias($alias);

        return $this->json([
            'payload' => [
                'id' => $categoryWebpage->getWebpage()->getId(),
                'pagetitle' => $categoryWebpage->getWebpage()->getPagetitle(),
                'description' => $categoryWebpage->getWebpage()->getDescription(),
                'headline' => $categoryWebpage->getWebpage()->getHeadline(),
                'categoryNameSingle' => $categoryWebpage->getCategory()->getNameSingle(),
                'productCategoryId' => $categoryWebpage->getCategory()->getId()
            ]
        ]);
    }
}
