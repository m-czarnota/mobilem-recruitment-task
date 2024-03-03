<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement\Response;

use DateTimeImmutable;

class ResponseDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public float $cost,
        public DateTimeImmutable $postedAt,

        /** @var array<int, ResponseAnnouncementFileDto> $files */
        public array $files = [],
    ) {
    }
}
