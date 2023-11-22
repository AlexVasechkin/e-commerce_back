<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function home()
    {
        return $this->redirectToRoute('control_panel__login');
    }

    /**
     * @Route("/test", name="app_test", methods={"GET"})
     */
    public function test(ProductRepository $productRepository)
    {
        return $this->json(
//            $productRepository->fetchProductData(
                $productRepository->getFullIdList()
//            )
        );
    }
}
