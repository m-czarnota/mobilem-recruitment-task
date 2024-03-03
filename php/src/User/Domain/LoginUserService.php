<?php

declare(strict_types=1);

namespace App\User\Domain;

readonly class LoginUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    /**
     * @throws LoginUserException
     */
    public function execute(string $email, string $plainPassword): User
    {
        $user = $this->userRepository->findOneByEmail($email);
        if (!$user) {
            throw new LoginUserException('Wrong email or password');
        }

        $isPasswordValid = $this->userPasswordHasher->isPasswordValid($user, $plainPassword);
        if (!$isPasswordValid) {
            throw new LoginUserException('Wrong email or password');
        }

        return $user;
    }
}
