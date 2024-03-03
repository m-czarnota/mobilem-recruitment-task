<?php

declare(strict_types=1);

namespace App\User\Domain;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function findOneByEmail(string $email): ?User;
}
