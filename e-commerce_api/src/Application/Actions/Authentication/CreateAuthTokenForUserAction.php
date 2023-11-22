<?php

namespace App\Application\Actions\Authentication;

use App\Application\Services\Redis\RedisClient;
use App\Repository\UserRepository;
use Exception;
use Firebase\JWT\JWT;
use Faker;

class CreateAuthTokenForUserAction
{
    private Faker\Generator $generator;

    private UserRepository $userRepository;

    private RedisClient $redis;

    public function __construct(
        UserRepository $userRepository,
        RedisClient $redis
    ) {
        $this->generator = Faker\Factory::create('ru_RU');
        $this->userRepository = $userRepository;
        $this->redis = $redis;
    }

    public function execute(string $email): string
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (is_null($user)) {
            throw new \Exception(sprintf('User[email: "%s"] not found', $email));
        }

        $now = new \DateTime();
        $hash = hash('sha256', sprintf('%s', $now->getTimestamp()));

        $getCacheKey = fn(string $userCode) => sprintf('auth_email_%s', $userCode);

        $key = $this->redis->client->get($getCacheKey($user->getCode()));
        if (!is_string($key)) {
            $key = serialize([
                'value' => $this->generator->password(20),
                'hash' => $hash
            ]);

            $this->redis->client->set($getCacheKey($user->getCode()), $key);
            $this->redis->client->expire($getCacheKey($user->getCode()), 60 * 3);
        }
        $key = unserialize($key);

        if ($hash === $key['hash']) {
            $payload = [
                'sub' => $user->getCode(),
                'exp' => date_add($now, new \DateInterval(sprintf('PT%dS', 60 * 3)))->getTimestamp()
            ];

            return JWT::encode($payload, $key['value'], 'HS512');

        } else {
            throw new Exception('Auth token already exists');
        }
    }
}
