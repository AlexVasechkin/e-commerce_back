<?php

namespace App\Controller\Pages;

use App\Application\Services\Redis\RedisClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Route("/cp-logout", name="cp__logout", methods={"GET"})
     */
    public function logoutFromControlPanel(
        RedisClient $redisClient,
        Request $httpRequest
    ) {
        $httpResponse = new RedirectResponse(
            $this->generateUrl('control_panel__login')
        );

        $redisClient->client->del(sprintf('cp:access-token:%s', $httpRequest->cookies->get('cp-user-code')));

        $httpResponse->headers->clearCookie('cp-user-code');

        return $httpResponse;
    }
}
