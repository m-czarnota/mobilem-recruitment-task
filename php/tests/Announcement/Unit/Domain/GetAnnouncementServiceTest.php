<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Unit\Domain;

use App\Announcement\Domain\AnnouncementNotFoundException;
use App\Announcement\Domain\AnnouncementNotValidException;
use App\Announcement\Domain\AnnouncementRepositoryInterface;
use App\Announcement\Domain\GetAnnouncementService;
use App\Tests\Announcement\Stub\AnnouncementStub;
use PHPUnit\Framework\TestCase;

class GetAnnouncementServiceTest extends TestCase
{
    private readonly AnnouncementRepositoryInterface $announcementRepository;
    private readonly GetAnnouncementService $service;

    protected function setUp(): void
    {
        $this->announcementRepository = $this->createMock(AnnouncementRepositoryInterface::class);
        $this->service = new GetAnnouncementService(
            $this->announcementRepository,
        );
    }

    /**
     * @throws AnnouncementNotFoundException
     * @throws AnnouncementNotValidException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(string $id, string $exception, string $exceptionMessage): void
    {
        $this->announcementRepository
            ->method('findOneById')
            ->willReturn(is_numeric($id) ? AnnouncementStub::createExample() : null);

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute($id);
    }

    public static function executeValidationsDataProvider(): array
    {
        return [
            'announcement does not exist' => [
                'id' => 'fdg',
                'exception' => AnnouncementNotFoundException::class,
                'exceptionMessage' => 'Announcement fdg does not exist',
            ],
        ];
    }
}
