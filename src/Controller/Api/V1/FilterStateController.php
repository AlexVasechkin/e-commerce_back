<?php

namespace App\Controller\Api\V1;

use App\Entity\FilterState;
use App\Repository\FilterStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class FilterStateController extends AbstractController
{
    private function serialize(FilterState $filterState): array
    {
        return [
            'id' => $filterState->getId()->toBase32(),
            'name' => $filterState->getName(),
            'state' => $filterState->getState()
        ];
    }

    /**
     * @Route("/api/v1/private/filter-state/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        FilterStateRepository $filterStateRepository
    ) {
        $rp = $httpRequest->toArray();

        $filterState = $filterStateRepository->save(
            (new FilterState())
                ->setName($rp['name'])
                ->setState($rp['state'])
        );

        return $this->json($this->serialize($filterState));
    }

    /**
     * @Route("/api/v1/private/filter-state/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        FilterStateRepository $filterStateRepository
    ) {
        $rp = $httpRequest->toArray();
        $keys = array_keys($rp);

        $filterState = $filterStateRepository->findOneByIdOrFail(Ulid::fromBase32($rp['id']));

        if (in_array('name', $keys)) {
            $filterState->setName($rp['name'] ?? $filterState->getName());
        }

        if (in_array('state', $keys)) {
            $filterState->setState($rp['state'] ?? $filterState->getState());
        }

        $filterStateRepository->save($filterState);

        return $this->json($this->serialize($filterState));
    }

    /**
     * @Route("/api/v1/private/filter-state/{id}", methods={"GET"})
     */
    public function getInstance($id, FilterStateRepository $filterStateRepository)
    {
        return $this->json(
            $this->serialize(
                $filterStateRepository->findOneByIdOrFail(Ulid::fromBase32($id))
            )
        );
    }

    /**
     * @Route("/api/v1/private/filter-state/delete", methods={"POST"})
     */
    public function delete(
        Request $httpRequest,
        FilterStateRepository $filterStateRepository
    ) {
        $rp = $httpRequest->toArray();

        $filterStateRepository->remove(
            $filterStateRepository->findOneByIdOrFail(Ulid::fromBase32($rp['id']))
        );

        return new Response('ok');
    }
}
