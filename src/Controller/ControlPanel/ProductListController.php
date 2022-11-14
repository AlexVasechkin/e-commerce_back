<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductListController extends AbstractController
{
    /**
     * @Route("/control-panel/products", methods={"GET"})
     */
    public function main()
    {
        return $this->render('cp/product-list.html.twig', []);
    }
}
