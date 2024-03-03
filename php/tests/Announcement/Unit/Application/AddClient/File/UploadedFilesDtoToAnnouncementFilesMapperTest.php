<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Unit\Application\AddClient\File;

use App\Announcement\Application\AddAnnouncement\File\UploadedFilesDtoToAnnouncementFilesMapper;
use App\Announcement\Domain\AnnouncementFileNotValidException;
use App\Announcement\Domain\FileValidator;
use App\Tests\Announcement\Stub\UploadedFileDtoStub;
use PHPUnit\Framework\TestCase;

class UploadedFilesDtoToAnnouncementFilesMapperTest extends TestCase
{
    private readonly UploadedFilesDtoToAnnouncementFilesMapper $service;

    protected function setUp(): void
    {
        $this->service = new UploadedFilesDtoToAnnouncementFilesMapper();
    }

    /**
     * @throws AnnouncementFileNotValidException
     *
     * @dataProvider executeDataProvider
     */
    public function testExecute(array $uploadedFilesData): void
    {
        $uploadedFilesDto = UploadedFileDtoStub::createMultipleFromArrayData($uploadedFilesData);
        $clientFiles = $this->service->execute(...$uploadedFilesDto);

        self::assertCount(count($uploadedFilesData), $clientFiles);
    }

    public static function executeDataProvider(): array
    {
        return [
            '3 files, all valid' => [
                'uploadedFilesData' => [
                    [
                        'clientName' => 'funny-birds.png',
                        'mimeType' => 'image/png',
                        'size' => 4212,
                        'realPath' => '/tmp/2vdfg',
                    ],
                    [
                        'clientName' => 'funny-dogs.png',
                        'mimeType' => 'image/png',
                        'size' => 42121,
                        'realPath' => '/other-tmp/dfgsdf',
                    ],
                    [
                        'clientName' => 'funny-cats.jpeg',
                        'mimeType' => 'image/jpeg',
                        'size' => 42376,
                        'realPath' => '/tmp/2vdgeg',
                    ],
                ],
            ],
            'no files' => [
                'uploadedFilesData' => [],
            ],
        ];
    }

    /**
     * @throws AnnouncementFileNotValidException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(array $uploadedFilesData, string $exception, string $exceptionMessage): void
    {
        $uploadedFilesDto = UploadedFileDtoStub::createMultipleFromArrayData($uploadedFilesData);

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute(...$uploadedFilesDto);
    }

    public static function executeValidationsDataProvider(): array
    {
        $maxSize = FileValidator::getSizeAsMb(FileValidator::MAX_SIZE);

        return [
            'wrong first 2 files of 3' => [
                'uploadedFilesData' => [
                    [
                        'clientName' => 'funny-birds.png',
                        'mimeType' => 'image/png',
                        'size' => 4212543654757,
                        'realPath' => '/tmp/2vdfg',
                    ],
                    [
                        'clientName' => 'report-2024.pdf',
                        'mimeType' => 'application/pdf',
                        'size' => 42121,
                        'realPath' => '/other-tmp/dfgsdf',
                    ],
                    [
                        'clientName' => 'funny-cats.jpeg',
                        'mimeType' => 'image/jpeg',
                        'size' => 42376,
                        'realPath' => '/tmp/2vdgeg',
                    ],
                ],
                'exception' => AnnouncementFileNotValidException::class,
                'exceptionMessage' => json_encode([
                    [
                        'size' => "File is too large, allowed size: `$maxSize` MB, current size: `4017394.69` MB",
                    ],
                    [
                        'mimeType' => 'File is not image',
                    ],
                    [],
                ]),
            ],
        ];
    }
}
