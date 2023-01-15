<?php

namespace App\Application\Actions\Authentication;

use App\Application\Actions\Authentication\DTO\AuthenticateUserByAuthTokenRequest;
use App\Application\Actions\Authentication\DTO\AuthenticateUserByAuthTokenResponse;
use App\Application\Services\JWT\ExtractPayloadService;
use App\Application\Services\Redis\RedisClient;
use App\Repository\UserRepository;
use DateTime;
use Faker;
use Firebase\JWT\JWT;

class AuthenticateUserByAuthTokenAction
{
    private CheckAuthTokenAction $checkAuthTokenAction;

    private UserRepository $userRepository;

    private ExtractPayloadService $extractPayloadService;

    private RedisClient $redis;

    private Faker\Generator $faker;

    public function __construct(
        CheckAuthTokenAction $checkAuthTokenAction,
        UserRepository $userRepository,
        ExtractPayloadService $extractPayloadService,
        RedisClient $redis
    ) {
        $this->checkAuthTokenAction = $checkAuthTokenAction;
        $this->userRepository = $userRepository;
        $this->extractPayloadService = $extractPayloadService;
        $this->redis = $redis;
        $this->faker = Faker\Factory::create('ru_RU');
    }

    public function execute(AuthenticateUserByAuthTokenRequest $request): AuthenticateUserByAuthTokenResponse
    {
        $authTokenData = $this->checkAuthTokenAction->execute($request->getToken());

        $payload = $this->extractPayloadService->extractPayload($request->getToken());

        $response = new AuthenticateUserByAuthTokenResponse();
        $response->setUser($this->userRepository->findOneBy(['code' => $payload['sub'] ?? null]));

        $response->getUser()->setSecret($this->faker->password(20));
        $this->userRepository->save($response->getUser(), true);

        $now = new DateTime();
        $accessToken = [
            'sub' => $response->getUser()->getCode(),
            'exp' => date_add($now, new \DateInterval(sprintf('PT%dS', 60 * 60 * 12)))->getTimestamp()
        ];

        $response->setToken(
            JWT::encode($accessToken, $response->getUser()->getSecret(), 'HS512')
        );

        $this->redis->client->set(sprintf('cp:access-token:%s', $response->getUser()->getCode()), $response->getToken());

        $this->redis->client->del(sprintf('auth_email_%s', $payload['sub']));

        return $response;
    }
}
