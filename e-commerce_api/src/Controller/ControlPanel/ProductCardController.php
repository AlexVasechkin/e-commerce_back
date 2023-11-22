<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductCardController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/control-panel/product-card")
     */
    public function main()
    {
        return $this->render('cp/product-card.html.twig', [
            'title' => 'Редактируем товар'
        ]);
    }
}
