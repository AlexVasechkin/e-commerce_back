<?php

namespace App\Controller\ControlPanel;

use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ImportProductsController extends AbstractController
{
    /**
     * @Route("/control-panel/import-products", methods={"GET"})
     */
    public function importPage(ProductCategoryRepository $productCategoryRepository)
    {
        return $this->render('cp/import-products.html.twig', [
            'categories' => $productCategoryRepository->findAll()
        ]);
    }
}
