<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Stub;

use App\Announcement\Domain\AnnouncementFile;
use App\Announcement\Domain\AnnouncementFileNotValidException;

class AnnouncementFileStub
{
    /**
     * @return array<int, AnnouncementFile>
     *
     * @throws AnnouncementFileNotValidException
     */
    public static function createMultipleFromArrayData(array $announcementFilesData): array
    {
        return array_map(fn (array $announcementFileData) => self::createFromArrayData($announcementFileData), $announcementFilesData);
    }

    /**
     * @throws AnnouncementFileNotValidException
     */
    public static function createFromArrayData(array $clientFileData): AnnouncementFile
    {
        return new AnnouncementFile(
            $clientFileData['id'] ?? null,
            $clientFileData['name'],
            $clientFileData['path'],
        );
    }
}
