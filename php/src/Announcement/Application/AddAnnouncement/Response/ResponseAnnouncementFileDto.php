<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement\Response;

class ResponseAnnouncementFileDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $path,
    ) {
    }
}
