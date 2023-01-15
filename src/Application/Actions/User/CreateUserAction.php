<?php

namespace App\Application\Actions\User;

use App\Entity\Contracts\PutUserInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Exception;

class CreateUserAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(PutUserInterface $request): User
    {
        $user = $this->userRepository->findOneBy(['email' => $request->getEmail()]);

        if ($user) {
            throw new Exception(sprintf('User[email: %s] already exists.', $request->getEmail()));
        }

        $user = $this->userRepository->findOneBy(['username' => $request->getUserName()]);

        if ($user) {
            throw new Exception(sprintf('User[username: %s] already exists.', $request->getUserName()));
        }

        $user = (new User())->put($request);
        return $this->userRepository->save($user, true);
    }
}
