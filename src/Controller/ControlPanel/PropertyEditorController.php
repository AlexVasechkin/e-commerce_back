<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PropertyEditorController extends AbstractController
{
    /**
     * @Route("/control-panel/properties", methods={"GET"}, name="control-panel__properties")
     */
    public function main()
    {
        return $this->render('cp/property-editor.html.twig', []);
    }
}
