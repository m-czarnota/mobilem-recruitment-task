<?php

declare(strict_types=1);

namespace App\User\Domain;

interface UserPasswordHasherInterface
{
    public function hash(User $user, string $password): string;

    public function isPasswordValid(User $user, string $plainPassword): bool;
}
