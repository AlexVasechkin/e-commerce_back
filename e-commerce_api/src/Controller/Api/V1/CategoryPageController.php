<?php

namespace App\Controller\Api\V1;

use App\Entity\CategoryWebpage;
use App\Entity\Webpage;
use App\Repository\CategoryWebpageRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\WebpageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryPageController extends AbstractController
{
    /**
     * @Route("/api/v1/private/category-page/create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        WebpageRepository $webpageRepository,
        ProductCategoryRepository $productCategoryRepository,
        CategoryWebpageRepository $categoryWebpageRepository
    ) {
        $rp = $httpRequest->toArray();

        $webpage = (new Webpage())
            ->setName($rp['name'])
            ->setAlias($rp['alias'])
            ->setIsActive(false)
            ->setPagetitle($rp['pagetitle'] ?? null)
            ->setHeadline($rp['headline'] ?? null)
            ->setDescription($rp['description'] ?? null)
            ->setContent($rp['content'] ?? null)
        ;

        if (   isset($rp['parentId'])
            && is_int($rp['parentId'])
        ) {
            $parent = $webpageRepository->findOneBy(['id' => $rp['parentId']]);
            if ($parent) {
                $webpage->setParent($parent);
            }
        }

        $webpageRepository->save($webpage, true);

        $category = $productCategoryRepository->findOneBy(['id' => $rp['categoryId']]);

        $categoryWebpage = (new CategoryWebpage())
            ->setCategory($category)
            ->setWebpage($webpage)
        ;

        $categoryWebpageRepository->save($categoryWebpage, true);

        return $this->json([
            'id' => $webpage->getId()
        ]);
    }


}
