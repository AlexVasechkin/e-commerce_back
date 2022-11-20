<?php

namespace App\Controller\Api\V1;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @Route("/api/v1/private/property/create", methods={"POST"})
     */
    public function create(Request $httpRequest, PropertyRepository $propertyRepository)
    {
        $rp = $httpRequest->toArray();

        $p = (new Property())
            ->setName($rp['name'])
        ;

        $propertyRepository->save($p, true);

        return $this->json(['id' => $p->getId()]);
    }

    /**
     * @Route("/api/v1/private/property/dict", methods={"GET"})
     */
    public function getDict(PropertyRepository $propertyRepository)
    {
        return $this->json([
            'payload' => array_map(function (Property $property) {
                return [
                    'value' => $property->getId(),
                    'caption' => mb_convert_case($property->getName(), MB_CASE_TITLE, 'UTF-8')
                ];
            }, $propertyRepository->findAll())
        ]);
    }

    /**
     * @Route("/api/v1/private/property/list", methods={"POST"})
     */
    public function getList(Connection $connection)
    {
        $query = implode(PHP_EOL, [
            "select",
            "     p.id                       as id",
            "    ,p.name                     as name",
            "    ,coalesce(q.categories, '') as categories",
            "  from property as p",
            "  left join (select",
            "                  cp.property_id           as property_id",
            "                 ,string_agg(pg.name, ',') as categories",
            "               from category_property as cp",
            "               inner join product_category as pg on pg.id = cp.category_id",
            "               group by",
            "                 cp.property_id",
            "            ) as q on q.property_id = p.id",
            ";",
        ]);

        return $this->json(['payload' => $connection->fetchAllAssociative($query)]);
    }

    /**
     * @Route("/api/v1/private/property/update", methods={"POST"})
     */
    public function update(Request $httpRequest, PropertyRepository $propertyRepository)
    {
        $rp = $httpRequest->toArray();

        $id = $rp['id'];

        $p = $propertyRepository->findOneBy(['id' => $id]);

        if (is_null($p)) {
            throw new NotFoundHttpException();
        }

        isset($rp['name']) ? $p->setName($rp['name']) : null;

        $propertyRepository->save($p, true);

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/api/v1/private/property/remove", methods={"POST"})
     */
    public function remove(Request $httpRequest, PropertyRepository $propertyRepository)
    {
        $id = $httpRequest->toArray()['id'] ?? 0;

        $p = $propertyRepository->findOneBy(['id' => $id]);

        if (is_null($p)) {
            throw new NotFoundHttpException();
        }

        $propertyRepository->remove($p, true);

        return new Response();
    }
}
