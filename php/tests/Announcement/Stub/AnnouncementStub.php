<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Stub;

use App\Announcement\Domain\Announcement;
use App\Announcement\Domain\AnnouncementFileNotValidException;
use App\Announcement\Domain\AnnouncementFilesCountExceededException;
use App\Announcement\Domain\AnnouncementNotValidException;

class AnnouncementStub
{
    /**
     * @throws AnnouncementNotValidException
     * @throws AnnouncementFileNotValidException
     * @throws AnnouncementFilesCountExceededException
     */
    public static function createFromArrayData(array $announcementData): Announcement
    {
        $announcement = new Announcement(
            $announcementData['id'] ?? null,
            $announcementData['title'],
            $announcementData['description'],
            $announcementData['cost'],
        );

        foreach ($announcementData['files'] as $fileData) {
            $announcementFile = AnnouncementFileStub::createFromArrayData($fileData);
            $announcement->addFile($announcementFile);
        }

        return $announcement;
    }

    /**
     * @throws AnnouncementNotValidException
     */
    public static function createExample(?string $id = null): Announcement
    {
        return new Announcement(
            $id,
            'Example Announcement',
            'Great deal! Only 100 onions!',
            100,
        );
    }
}
