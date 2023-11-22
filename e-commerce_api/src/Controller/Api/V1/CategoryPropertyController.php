<?php

namespace App\Controller\Api\V1;

use App\Entity\CategoryProperty;
use App\Repository\CategoryPropertyRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\PropertyRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryPropertyController extends AbstractController
{
    /**
     * @Route("/api/v1/private/category-property/list", methods={"POST"})
     */
    public function getList(Request $httpRequest, Connection $connection)
    {
        $rp = $httpRequest->toArray();

        $q = implode(PHP_EOL, [
            "select",
            "     cp.id",
            "    ,cp.category_id",
            "    ,cp.property_id",
            "    ,cp.type",
            "    ,cp.is_active",
            "    ,cp.unit",
            "    ,cp.group_name",
            "    ,coalesce(pc.name, '') as category__name",
            "    ,coalesce(p.name, '')  as property__name",
            "  from category_property as cp",
            "  left join property as p on p.id = cp.property_id",
            "  left join product_category as pc on pc.id = cp.category_id",
            "  where 1 = 1",
            is_array($rp['productCategoryIdList']) && (count($rp['productCategoryIdList']) > 0)
                ? sprintf('and cp.category_id in (%s)', implode(',', array_column($rp['productCategoryIdList'], 'value')))
                : '',
            "  order by",
            "     cp.category_id",
            "    ,cp.group_name",
            "    ,cp.is_active desc",
            ";",
        ]);

        return $this->json(['payload' => array_map(function (array $row) {
            return [
                'id' => $row['id'],
                'categoryId' => $row['category_id'],
                'propertyId' => $row['property_id'],
                'type' => $row['type'],
                'isActive' => $row['is_active'],
                'unit' => $row['unit'],
                'groupName' => $row['group_name'],
                'categoryName' => $row['category__name'],
                'propertyName' => $row['property__name']
            ];
        }, $connection->fetchAllAssociative($q))]);
    }

    /**
     * @Route("/api/v1/private/category-property/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        CategoryPropertyRepository $categoryPropertyRepository,
        ProductCategoryRepository $productCategoryRepository,
        PropertyRepository $propertyRepository
    ) {
        $rp = $httpRequest->toArray();
        $cp = $categoryPropertyRepository->findOneBy(['id' => $rp['id'] ?? 0]);

        if (is_null($cp)) {
            throw new NotFoundHttpException();
        }

        if (isset($rp['categoryId'])) {
            $category = $productCategoryRepository->findOneBy(['id' => $rp['categoryId']]);

            if ($category) {
                $cp->setCategory($category);
            }
        }

        if (isset($rp['propertyId'])) {
            $p = $propertyRepository->findOneBy(['id' => $rp['propertyId']]);

            if ($p) {
                $cp->setProperty($p);
            }
        }

        isset($rp['type']) ? $cp->setType($rp['type']) : null;

        isset($rp['groupName']) ? $cp->setGroupName($rp['groupName']) : null;

        isset($rp['unit']) ? $cp->setUnit($rp['unit'] === '' ? null : $rp['unit']) : null;

        isset($rp['isActive']) ? $cp->setIsActive($rp['isActive']) : null;

        $categoryPropertyRepository->save($cp, true);

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/api/v1/private/category-property/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        CategoryPropertyRepository $categoryPropertyRepository,
        ProductCategoryRepository $productCategoryRepository,
        PropertyRepository $propertyRepository
    ) {
        $rp = $httpRequest->toArray();

        $pc = $productCategoryRepository->findOneBy(['id' => $rp['categoryId']]);

        $p = $propertyRepository->findOneBy(['id' => $rp['propertyId']]);

        $cp = (new CategoryProperty())
            ->setCategory($pc)
            ->setProperty($p)
            ->setIsActive(true)
            ->setGroupName($rp['groupName'] === '' ? null : $rp['groupName'])
            ->setType($rp['type'])
            ->setUnit($rp['unit'] === '' ? null : $rp['unit'])
        ;

        $categoryPropertyRepository->save($cp, true);

        return $this->json(['id' => $cp->getId()]);
    }
}
