<?php

namespace App\Controller\Api\V1;

use App\Entity\ProductWebpage;
use App\Entity\Webpage;
use App\Repository\ProductRepository;
use App\Repository\ProductWebpageRepository;
use App\Repository\WebpageRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPageController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-page/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        ProductRepository $productRepository,
        WebpageRepository $webpageRepository,
        ProductWebpageRepository $productWebpageRepository
    ) {
        $rp = $httpRequest->toArray();

        $product = $productRepository->findOneBy(['id' => $rp['productId']]);

        $webpage = (new Webpage())
            ->setName($rp['name'])
            ->setAlias($rp['alias'])
            ->setIsActive(false)
        ;

        $webpageRepository->save($webpage, true);

        $productWebpage = (new ProductWebpage())
            ->setProduct($product)
            ->setWebpage($webpage)
        ;

        $productWebpageRepository->save($productWebpage, true);

        return $this->json([
            'id' => $webpage->getId()
        ]);
    }

    /**
     * @Route("/api/v1/private/product-page/update", methods={"POST"})
     */
    public function update(Request $httpRequest, WebpageRepository $webpageRepository)
    {
        $rp = $httpRequest->toArray();

        $page = $webpageRepository->findOneBy(['id' => $rp['id']]);

        if (is_null($page)) {
            throw new Exception(sprintf('Webpage[id: %s] not found', $rp['id']));
        }

        isset($rp['name']) ? $page->setName($rp['name']) : null;

        isset($rp['pagetitle']) ? $page->setPagetitle($rp['pagetitle']) : null;

        isset($rp['headline']) ? $page->setHeadline($rp['headline']) : null;

        isset($rp['description']) ? $page->setDescription($rp['description']) : null;

        isset($rp['content']) ? $page->setContent($rp['content']) : null;

        isset($rp['alias']) ? $page->setAlias($rp['alias']) : null;

        isset($rp['isActive']) ? $page->setIsActive($rp['isActive']) : null;

        $webpageRepository->save($page, true);

        return new Response();
    }

    private function serialize(?Webpage $webpage): array
    {
        return array_merge(
            $this->serializeShort($webpage),
            [
                'description' => $webpage ? $webpage->getDescription() : null,
                'content' => $webpage ? $webpage->getContent() : null,
            ]
        );
    }

    private function serializeShort(?Webpage $webpage): array
    {
        return [
            'id' => $webpage ? $webpage->getId() : null,
            'name' => $webpage ? $webpage->getName() : null,
            'pagetitle' => $webpage ? $webpage->getPagetitle() : null,
            'headline' => $webpage ? $webpage->getHeadline() : null,
            'alias' => $webpage ? $webpage->getAlias() : null,
            'isActive' => $webpage ? $webpage->getIsActive() : null,
        ];
    }

    /**
     * @Route("/api/v1/private/product-page/{id}", methods={"GET"})
     */
    public function getInstance($id, WebpageRepository $webpageRepository)
    {
        $page = $webpageRepository->findOneBy(['id' => $id]);

        return $this->json([
            'payload' => $page ? $this->serialize($page) : null
        ]);
    }

    /**
     * @Route("/api/v1/private/product-page-by-product/{productId}", methods={"GET"})
     */
    public function getOneByProduct(
        $productId,
        ProductRepository $productRepository
    ) {
        $product = $productRepository->findOneBy(['id' => $productId]);
        $productWebpage = $product ? $product->getProductWebpage() : null;
        $webpage = $productWebpage ? $productWebpage->getWebpage() : null;

        return $this->json([
            'payload' => $webpage ? $this->serializeShort($webpage) : null
        ]);
    }
}
