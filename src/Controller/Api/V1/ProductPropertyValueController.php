<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\Product\Elasticsearch\DTO\UpdateElasticsearchProductRequest;
use App\Application\Actions\Product\Elasticsearch\UpdateElasticsearchProductAction;
use App\Entity\CategoryProperty;
use App\Entity\ProductPropertyValue;
use App\Repository\CategoryPropertyRepository;
use App\Repository\ProductPropertyValueRepository;
use App\Repository\ProductRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductPropertyValueController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-property-value/save", methods={"POST"})
     */
    public function save(
        Request $httpRequest,
        CategoryPropertyRepository $categoryPropertyRepository,
        ProductRepository $productRepository,
        ProductPropertyValueRepository $productPropertyValueRepository,
        UpdateElasticsearchProductAction $updateElasticsearchProductAction
    ) {
        $requestParams = $httpRequest->toArray();

        $productId = $requestParams['productId'] ?? null;
        $propertyId = $requestParams['propertyId'] ?? null;
        $value = $requestParams['value'];

        $property = $categoryPropertyRepository->findOneBy(['id' => $propertyId]);
        if (is_null($property)) {
            throw new Exception('Property not found');
        }

        $product = $productRepository->findOneBy(['id' => $productId]);

        $propertyValue = $productPropertyValueRepository->findOneBy([
            'product' => $product,
            'property' => $property
        ]);

        if (is_null($propertyValue)) {
            $propertyValue = (new ProductPropertyValue())
                ->setProduct($product)
                ->setProperty($property)
            ;
        }

        $productPropertyValueRepository->save(
            $propertyValue->setValue($property, $value)
            , true
        );

        $updateElasticsearchProductAction->execute(
            (new UpdateElasticsearchProductRequest($productId))
                ->addProperty([
                    'propertyId' => intval($propertyId),
                    'value' => $value
                ])
        );

        return $this->json([
            'id' => $propertyValue->getId()->toBase32()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-property-values/{id}", methods={"GET"})
     */
    public function list(
        $id,
        ProductRepository $productRepository,
        ProductPropertyValueRepository $productPropertyValueRepository
    ) {
        $product = $productRepository->findOneBy(['id' => $id]);

        $properties = [];
        foreach ($product->getProductCategoryItems() as $productCategoryItem) {
            $properties = array_merge(
                $properties,
                $productCategoryItem->getCategory()->getCategoryProperties()->toArray()
            );
        }

        $values = $productPropertyValueRepository->fetchByProductAndProperties($product, $properties);

        return $this->json([
            'payload' => array_map(function (CategoryProperty $property) use ($values) {

                $value = array_values(array_filter($values, function (ProductPropertyValue $v) use ($property) {
                    return $v->getProperty()->getId() === $property->getId();
                }))[0] ?? null;

                return [
                    'id' => $value ? $value->getId()->toBase32() : null,
                    'type' => $property->getType(),
                    'unit' => $property->getUnit(),
                    'value' => $value ? $value->getValue() : null,
                    'propertyId' => $property->getId(),
                    'propertyName' => $property->getProperty()->getName(),
                    'groupName' => $property->getGroupName()
                ];
            }, $properties)
        ]);
    }
}
