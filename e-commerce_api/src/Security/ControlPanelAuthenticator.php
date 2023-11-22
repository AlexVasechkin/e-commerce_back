<?php

namespace App\Security;

use App\Application\Services\Redis\RedisClient;
use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ControlPanelAuthenticator extends AbstractAuthenticator
{
    private UserRepository $userRepository;

    private RedisClient $redis;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        UserRepository $userRepository,
        RedisClient $redis,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->redis = $redis;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        preg_match('|^/control-panel|', $request->getRequestUri(), $matches1);
        preg_match('|^/api/v1/private|', $request->getRequestUri(), $matches2);
        return isset($matches1[0]) || isset($matches2[0]);
    }

    public function authenticate(Request $request): Passport
    {
        $userCode = $request->cookies->get('cp-user-code');
        if (!is_string($userCode)) {
            return new SelfValidatingPassport(new UserBadge(''));
        }

        $accessTokenString = $this->redis->client->get(sprintf('cp:access-token:%s', $userCode));

        if (is_null($accessTokenString)) {
            return new SelfValidatingPassport(new UserBadge(''));
        }

        $user = $this->userRepository->findOneBy(['code' => $userCode]);

        if (is_null($user)) {
            return new SelfValidatingPassport(new UserBadge(''));
        }

        try {
            JWT::decode($accessTokenString, new Key($user->getSecret(), 'HS512'));
        } catch (\Throwable $e) {
            return new SelfValidatingPassport(new UserBadge(''));
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse(
            $this->urlGenerator->generate('cp__logout')
        );
    }
}
