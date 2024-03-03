<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\User\Domain\User;
use App\User\Domain\UserPasswordHasherInterface as DomainHahser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class SymfonyUserPasswordHasher implements DomainHahser
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function hash(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    public function isPasswordValid(User $user, string $plainPassword): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }
}
