<?php

namespace App\Controller\Api\V1;

use App\Entity\Webpage;
use App\Repository\WebpageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebpageController extends AbstractController
{
    /**
     * @Route("/api/v1/private/webpage/update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        WebpageRepository $webpageRepository
    ) {
        $rp = $httpRequest->toArray();

        $webpage = $webpageRepository->findOneBy(['id' => $rp['id'] ?? null]);

        (isset($rp['name']) && is_string($rp['name']))
            ? $webpage->setName($rp['name'])
            : null
        ;

        isset($rp['pagetitle'])
            ? $webpage->setPagetitle($rp['pagetitle'])
            : null
        ;

        isset($rp['headline'])
            ? $webpage->setHeadline($rp['headline'])
            : null
        ;

        isset($rp['description'])
            ? $webpage->setDescription($rp['description'])
            : null
        ;

        isset($rp['content'])
            ? $webpage->setContent($rp['content'])
            : null
        ;

        isset($rp['alias'])
            ? $webpage->setAlias($rp['alias'])
            : null
        ;

        isset($rp['isActive'])
            ? $webpage->setIsActive($rp['isActive'])
            : null
        ;

        if (isset($rp['parentId'])) {
            $parent = $webpageRepository->findOneBy(['id' => $rp['parentId']]);

            if ($parent) {
                $webpage->setParent($parent);
            }
        }

        $webpageRepository->save($webpage, true);

        return $this->json([]);
    }

    private function serialize(Webpage $webpage): array
    {
        return [
            'id' => $webpage->getId(),
            'name' => $webpage->getName(),
            'pagetitle' => $webpage->getPagetitle(),
            'headline' => $webpage->getHeadline(),
            'description' => $webpage->getDescription(),
            'content' => $webpage->getContent(),
            'alias' => $webpage->getAlias(),
            'isActive' => $webpage->getIsActive()
        ];
    }

    /**
     * @Route("/api/v1/private/webpage/{id}", methods={"GET", "POST"})
     */
    public function getInstance(
        $id,
        WebpageRepository $webpageRepository
    ) {
        $webpage = $webpageRepository->findOneBy(['id' => $id]);

        return $this->json([
            'payload' => $webpage ? $this->serialize($webpage) : []
        ]);
    }
}
