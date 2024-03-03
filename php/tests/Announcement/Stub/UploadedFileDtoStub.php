<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Stub;

use App\Announcement\Application\AddAnnouncement\File\UploadedFileDto;

class UploadedFileDtoStub
{
    /**
     * @return array<int, UploadedFileDto>
     */
    public static function createMultipleFromArrayData(array $uploadedFilesDtoData): array
    {
        return array_map(
            fn (array $uploadedFileDtoData) => self::createFromArrayData($uploadedFileDtoData),
            $uploadedFilesDtoData
        );
    }

    public static function createFromArrayData(array $uploadedFileDtoData): UploadedFileDto
    {
        return new UploadedFileDto(
            $uploadedFileDtoData['clientName'],
            $uploadedFileDtoData['mimeType'],
            $uploadedFileDtoData['size'],
            $uploadedFileDtoData['realPath'],
        );
    }
}
