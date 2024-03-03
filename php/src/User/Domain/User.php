<?php

declare(strict_types=1);

namespace App\User\Domain;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public readonly string $id;
    private string $email;
    private string $password;
    private array $roles;

    public function __construct(
        ?string $id,
        string $email,
        array $roles,
    ) {
        $this->id = $id ?? Uuid::uuid7()->toString();
        $this->email = $email;
        $this->roles = $roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // nothing to do
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
