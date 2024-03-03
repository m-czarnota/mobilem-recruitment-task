<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement\Response;

use App\Announcement\Domain\Announcement;
use App\Common\Domain\Route\RouteGeneratorInterface;
use App\Common\Domain\Route\RouteTypeEnum;

readonly class AnnouncementToResponseDtoMapper
{
    public function __construct(
        private RouteGeneratorInterface $routeGenerator,
    ) {
    }

    public function execute(Announcement $announcement): ResponseDto
    {
        $filesDto = [];
        foreach ($announcement->getFiles() as $clientFile) {
            $absoluteFilePath = $this->routeGenerator->execute(
                'api:v1:get_announcement_file',
                RouteTypeEnum::ABSOLUTE_URL,
                [
                    'announcementId' => $announcement->id,
                    'fileId' => $clientFile->id,
                ],
            );

            $filesDto[] = new ResponseAnnouncementFileDto(
                $clientFile->id,
                $clientFile->getName(),
                $absoluteFilePath,
            );
        }

        return new ResponseDto(
            $announcement->id,
            $announcement->title,
            $announcement->description,
            $announcement->cost,
            $announcement->postedAt,
            $filesDto,
        );
    }
}
