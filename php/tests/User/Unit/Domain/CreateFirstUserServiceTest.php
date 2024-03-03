<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\Domain;

use App\Tests\User\Stub\UserStub;
use App\User\Domain\CreateFirstUserService;
use App\User\Domain\FirstUserAlreadyExistException;
use App\User\Domain\UserPasswordHasherInterface;
use App\User\Domain\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreateFirstUserServiceTest extends TestCase
{
    private readonly ParameterBagInterface $parameterBag;
    private readonly UserRepositoryInterface $userRepository;
    private readonly UserPasswordHasherInterface $userPasswordHasher;
    private readonly CreateFirstUserService $service;

    protected function setUp(): void
    {
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->service = new CreateFirstUserService(
            $this->parameterBag,
            $this->userRepository,
            $this->userPasswordHasher,
        );
    }

    /**
     * @throws FirstUserAlreadyExistException
     */
    public function testExecute(): void
    {
        $this->parameterBag
            ->method('get')
            ->willReturnMap([
                ['user.default.email', 'user@default.com'],
                ['user.default.password', '12334'],
            ]);

        $this->userRepository
            ->method('findOneByEmail')
            ->willReturn(null);

        $this->userPasswordHasher
            ->method('hash')
            ->willReturn('hashed_password');

        $user = $this->service->execute();
        self::assertSame('user@default.com', $user->getUserIdentifier());
    }

    /**
     * @throws FirstUserAlreadyExistException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(?string $userId, string $exception, string $exceptionMessage): void
    {
        $this->parameterBag
            ->method('get')
            ->willReturnMap([
                ['user.default.email', 'user@default.com'],
                ['user.default.password', '12334'],
            ]);

        $this->userRepository
            ->method('findOneByEmail')
            ->willReturn($userId ? UserStub::createExampleUser($userId) : null);

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute();
    }

    public static function executeValidationsDataProvider(): array
    {
        return [
            'user already exists' => [
                'userId' => '1',
                'exception' => FirstUserAlreadyExistException::class,
                'exceptionMessage' => 'Already exist first user with ID 1',
            ],
        ];
    }
}
