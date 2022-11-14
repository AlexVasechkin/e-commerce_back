<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/control-panel", methods={"GET"}, name="control-panel__dashboard")
     */
    public function main()
    {
        return $this->render('cp/dashboard.html.twig', []);
    }
}
