<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\User;
use App\User\Domain\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $this->userRepository->findOneByEmail($user->getUserIdentifier());

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findOneByEmail($identifier);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        /* @var $user User */
        $user->setPassword($newHashedPassword);
    }
}
