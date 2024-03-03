<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

use SplFileInfo;

class AnnouncementFileDataDto
{
    public function __construct(
        public string $originalFilename,
        public SplFileInfo $fileInfo,
    ) {
    }
}
