<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Unit\Domain;

use App\Announcement\Domain\AnnouncementFileNotFoundException;
use App\Announcement\Domain\AnnouncementFileNotValidException;
use App\Announcement\Domain\AnnouncementFilesCountExceededException;
use App\Announcement\Domain\AnnouncementNotValidException;
use App\Announcement\Domain\AnnouncementRepositoryInterface;
use App\Announcement\Domain\GetAnnouncementFileService;
use App\Common\Domain\File\FileGetterInterface;
use App\Common\Domain\File\FileNotExistException;
use App\Tests\Announcement\Stub\AnnouncementStub;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class GetAnnouncementFileServiceTest extends TestCase
{
    private readonly AnnouncementRepositoryInterface $announcementRepository;
    private readonly FileGetterInterface $fileGetter;
    private readonly GetAnnouncementFileService $service;

    protected function setUp(): void
    {
        $this->announcementRepository = $this->createMock(AnnouncementRepositoryInterface::class);
        $this->fileGetter = $this->createMock(FileGetterInterface::class);

        $this->service = new GetAnnouncementFileService(
            $this->announcementRepository,
            $this->fileGetter,
        );
    }

    /**
     * @throws AnnouncementFileNotFoundException
     * @throws AnnouncementFileNotValidException
     * @throws AnnouncementFilesCountExceededException
     * @throws AnnouncementNotValidException
     * @throws FileNotExistException
     *
     * @dataProvider executeDataProvider
     */
    public function testExecute(array $announcementData, string $announcementId, string $fileId, string $exceptedOriginalFilename): void
    {
        $client = AnnouncementStub::createFromArrayData($announcementData);
        $this->announcementRepository
            ->method('findOneById')
            ->willReturnCallback(fn (string $param) => is_numeric($param) ? $client : null);

        $this->fileGetter
            ->method('execute')
            ->willReturn(new SplFileInfo('example'));

        $announcementFileDataDto = $this->service->execute($announcementId, $fileId);
        self::assertEquals($exceptedOriginalFilename, $announcementFileDataDto->originalFilename);
    }

    public static function executeDataProvider(): array
    {
        return [
            'announcement with 2 files' => [
                'announcementData' => [
                    'id' => '1',
                    'title' => 'I will sell Opel',
                    'description' => 'Great occasion! Only 100 onions!',
                    'cost' => 100,
                    'files' => [
                        [
                            'id' => '1.1',
                            'name' => 'teddy-bear.jpg',
                            'path' => 'path',
                        ],
                        [
                            'id' => '1.2',
                            'name' => 'teddy-bear.jpg',
                            'path' => 'path2',
                        ],
                    ],
                ],
                'announcementId' => '1',
                'fileId' => '1.1',
                'exceptedOriginalFilename' => 'teddy-bear.jpg',
            ],
        ];
    }

    /**
     * @throws AnnouncementFileNotFoundException
     * @throws AnnouncementFileNotValidException
     * @throws AnnouncementFilesCountExceededException
     * @throws AnnouncementNotValidException
     * @throws FileNotExistException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(
        array $announcementData,
        string $announcementId,
        string $fileId,
        string $exception,
        string $exceptionMessage
    ): void {
        $client = AnnouncementStub::createFromArrayData($announcementData);
        $this->announcementRepository
            ->method('findOneById')
            ->willReturnCallback(fn (string $param) => $client->id === $param ? $client : null);

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute($announcementId, $fileId);
    }

    public static function executeValidationsDataProvider(): array
    {
        return [
            'client does not exist' => [
                'announcementData' => [
                    'id' => '1',
                    'title' => 'I will sell Opel',
                    'description' => 'Great occasion! Only 100 onions!',
                    'cost' => 100,
                    'files' => [
                        [
                            'id' => '1.1',
                            'name' => 'teddy-bear.jpg',
                            'path' => 'path',
                        ],
                    ],
                ],
                'announcementId' => '234',
                'fileId' => '1.1',
                'exception' => AnnouncementFileNotFoundException::class,
                'exceptionMessage' => 'Not found file `1.1` for announcement `234`',
            ],
            'client file not found' => [
                'announcementData' => [
                    'id' => '1',
                    'title' => 'I will sell Opel',
                    'description' => 'Great occasion! Only 100 onions!',
                    'cost' => 100,
                    'files' => [
                        [
                            'id' => '1.1',
                            'name' => 'teddy-bear.jpg',
                            'path' => 'path',
                        ],
                    ],
                ],
                'announcementId' => '1',
                'fileId' => '1.231',
                'exception' => AnnouncementFileNotFoundException::class,
                'exceptionMessage' => 'Not found file `1.231` for announcement `1`',
            ],
        ];
    }
}
