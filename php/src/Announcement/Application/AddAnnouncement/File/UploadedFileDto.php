<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement\File;

class UploadedFileDto
{
    public function __construct(
        public string $clientName,
        public string $mimeType,
        public int $size,
        public string $realPath,
    ) {
    }
}
