<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\ProductGroup\CreateProductGroupAction;
use App\Application\Actions\ProductGroup\DTO\CreateProductGroupRequest;
use App\Entity\ProductGroup;
use App\Repository\ProductGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductGroupController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-group/create", methods={"POST"})
     */
    public function create(
        Request $request,
        CreateProductGroupAction $createProductGroupAction
    ) {
        $getName = function (array $requestParams): string {
            $value = $requestParams['name'] ?? null;

            if (!(   is_string($value)
                  && (strlen($value) > 0)
                 )
            ) {
                throw new \Exception('name: expected not empty string');
            }

            return $value;
        };

        $productGroup = $createProductGroupAction->execute(new CreateProductGroupRequest(
            $getName($request->toArray())
        ));

        return $this->json([
            'id' => $productGroup->getId()
        ]);
    }

    private function serialize(ProductGroup $productGroup): array
    {
        return [
            'id' => $productGroup->getId(),
            'name' => $productGroup->getName(),
            'isToHomepage' => $productGroup->isIsToHomepage(),
            'homepageSort' => $productGroup->getHomepageSort()
        ];
    }

    /**
     * @Route("/api/v1/public/product-group/list", methods={"POST"})
     */
    public function getList(
        Request $httpRequest,
        ProductGroupRepository $productGroupRepository
    ) {
        $rp = $httpRequest->toArray();

        if (   isset($rp['isToHomepage'])
            && is_bool($rp['isToHomepage'])
        ) {
            $dataSet = $productGroupRepository->findBy(['isToHomepage' => $rp['isToHomepage']]);
        } else {
            $dataSet = $productGroupRepository->findAll();
        }

        return $this->json([
            'payload' => array_map(fn(ProductGroup $item) => $this->serialize($item), $dataSet)
        ]);
    }

    /**
     * @Route("/api/v1/private/product-group/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        ProductGroupRepository $productGroupRepository
    ) {
        $rp = $httpRequest->toArray();

        $entity = $productGroupRepository->findOneByIdOrFail($rp['id']);

        if (isset($rp['name']) && is_string($rp['name'])) {
            $entity->setName($rp['name']);
        }

        if (isset($rp['isToHomepage']) && is_bool($rp['isToHomepage'])) {
            $entity->setIsToHomepage($rp['isToHomepage']);
        }

        if (isset($rp['homepageSort']) && is_int($rp['homepageSort'])) {
            $entity->setHomepageSort($rp['homepageSort']);
        }

        $productGroupRepository->save($entity, true);

        return new Response('ok');
    }

    /**
     * @Route("/api/v1/private/product-group/{id}", methods={"GET"})
     */
    public function getInstance(
        $id,
        ProductGroupRepository $productGroupRepository
    ) {
        return $this->json(
            $this->serialize(
                $productGroupRepository->findOneByIdOrFail(intval($id))
            )
        );
    }
}
