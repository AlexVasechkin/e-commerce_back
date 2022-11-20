<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryPropertyEditorController extends AbstractController
{
    /**
     * @Route("/control-panel/category-properties", methods={"GET"}, name="control-panel__category-properties")
     */
    public function main()
    {
        return $this->render('cp/category-property-editor.html.twig', []);
    }
}
