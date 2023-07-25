<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/cp-login", methods={"GET"}, name="control_panel__login")
     */
    public function login()
    {
        return $this->render('cp/login.html.twig', [
            'title' => 'Авторизация'
        ]);
    }
}
