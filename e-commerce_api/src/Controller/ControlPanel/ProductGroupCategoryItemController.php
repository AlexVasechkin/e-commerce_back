<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductGroupCategoryItemController extends AbstractController
{
    /**
     * @Route("/control-panel/group-category-editor", methods={"GET"}, name="control-panel__group-category-editor")
     */
    public function main()
    {
        return $this->render('cp/product-group-category-editor.html.twig', [
            'title' => 'Группы товаров по категориям'
        ]);
    }
}
