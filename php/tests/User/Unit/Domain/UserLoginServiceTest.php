<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\Domain;

use App\Tests\User\Stub\UserStub;
use App\User\Domain\LoginUserException;
use App\User\Domain\LoginUserService;
use App\User\Domain\UserPasswordHasherInterface;
use App\User\Domain\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UserLoginServiceTest extends TestCase
{
    private readonly UserRepositoryInterface $userRepository;
    private readonly UserPasswordHasherInterface $userPasswordHasher;
    private readonly LoginUserService $service;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->service = new LoginUserService(
            $this->userRepository,
            $this->userPasswordHasher,
        );
    }

    /**
     * @throws LoginUserException
     */
    public function testExecute(): void
    {
        $id = '1';
        $email = 'user@example.com';

        $this->userRepository
            ->method('findOneByEmail')
            ->willReturn(UserStub::createExampleUser($id, $email));

        $this->userPasswordHasher
            ->method('isPasswordValid')
            ->willReturn(true);

        $loggedUser = $this->service->execute($email, 'abc123');
        self::assertSame($id, $loggedUser->id);
        self::assertSame($email, $loggedUser->getUserIdentifier());
    }

    /**
     * @throws LoginUserException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(string $email, string $password, string $exception, string $exceptionMessage): void
    {
        $this->userRepository
            ->method('findOneByEmail')
            ->willReturn($email ? UserStub::createExampleUser() : null);

        $this->userPasswordHasher
            ->method('isPasswordValid')
            ->willReturn($password !== '');

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute($email, $password);
    }

    public static function executeValidationsDataProvider(): array
    {
        return [
            'user by email not found' => [
                'email' => '',
                'password' => 'abc123',
                'exception' => LoginUserException::class,
                'exceptionMessage' => 'Wrong email or password',
            ],
            'wrong password' => [
                'email' => 'user@example.com',
                'password' => '',
                'exception' => LoginUserException::class,
                'exceptionMessage' => 'Wrong email or password',
            ],
        ];
    }
}
