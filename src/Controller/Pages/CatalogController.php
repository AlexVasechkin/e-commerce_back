<?php

namespace App\Controller\Pages;

use App\Repository\WebpageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    /**
     * @Route("/catalog/{alias}", methods={"GET"})
     */
    public function main(
        $alias,
        WebpageRepository $webpageRepository
    ) {
        $webpage = $webpageRepository->findOneBy(['alias' => $alias]);

        if (is_null($webpage)) {
            throw new NotFoundHttpException();
        }

        return $this->render('client/base.html.twig', [
            'webpage' => $webpage
        ]);
    }
}
