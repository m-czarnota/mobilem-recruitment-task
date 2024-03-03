<?php

declare(strict_types=1);

namespace App\Tests\User\Stub;

use App\User\Domain\User;
use App\User\Domain\UserRoleEnum;

class UserStub
{
    public static function createExampleUser(?string $id = null, string $email = 'example@user.com'): User
    {
        return new User(
            $id,
            $email,
            [UserRoleEnum::USER->value],
        );
    }
}
