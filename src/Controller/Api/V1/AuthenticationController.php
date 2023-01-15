<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\Authentication\AuthenticateUserByAuthTokenAction;
use App\Application\Actions\Authentication\CreateAuthTokenForUserAction;
use App\Application\Actions\Authentication\DTO\AuthenticateUserByAuthTokenRequest;
use App\Application\Actions\Email\DTO\SendTextEmailRequest;
use App\Application\Actions\Email\SendTextEmailAction;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use Symfony\Component\HttpFoundation\Cookie;

class AuthenticationController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/api/v1/public/authenticate-by-auth-token", methods={"GET"}, name="cp__auth_by_token")
     */
    public function authenticateByAuthToken(
        Request $request,
        AuthenticateUserByAuthTokenAction $authenticateUserByAuthTokenAction
    ) {
        try {
            $authToken = $request->get('token');

            if (!is_string($authToken)) {
                throw new \Exception('token: expected string');
            }

            $response = $authenticateUserByAuthTokenAction->execute(
                new AuthenticateUserByAuthTokenRequest($authToken)
            );

            $httpResponse = new RedirectResponse(
                $this->generateUrl('control-panel__dashboard')
            );

            $httpResponse->headers->setCookie(
                new Cookie('cp-user-code', $response->getUser()->getCode())
            );

            return $httpResponse;

        } catch (\Throwable $e) {
            $this->logger->error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * @Route("/api/v1/public/auth-by-email", methods={"POST"})
     */
    public function authByEmail(
        Request $httpRequest,
        CreateAuthTokenForUserAction $createAuthTokenForUserAction,
        SendTextEmailAction $sendTextEmailAction,
        UserRepository $userRepository
    ) {
        try {
            $token = $createAuthTokenForUserAction->execute($httpRequest->get('email'));

            $user = $userRepository->findOneBy(['email' => $httpRequest->get('email')]);

            $sendTextEmailAction->execute(new SendTextEmailRequest(
                $user->getEmail(),
                'Вход в личный кабинет',
                'Чтобы авторизоваться переходи по ссылке',
                implode(PHP_EOL, [
                    '<p>Чтобы авторизоваться переходи по ссылке<br />',
                    sprintf('<a href="%s">войти</a>', '//' . $_SERVER['HTTP_HOST'] . $this->generateUrl('cp__auth_by_token') . '?token=' . $token),
                    '</p>'
                ])
            ));

            return new Response();

        } catch (Throwable $e) {
            throw $e;
        }
    }
}
