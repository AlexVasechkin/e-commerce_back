<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VendorListController extends AbstractController
{
    /**
     * @Route("/control-panel/vendors", methods={"GET"}, name="control-panel__vendors")
     */
    public function index()
    {
        return $this->render('cp/vendor-list.html.twig', []);
    }
}
