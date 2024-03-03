<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

use App\Common\Domain\File\FileGetterInterface;
use App\Common\Domain\File\FileNotExistException;

readonly class GetAnnouncementFileService
{
    public function __construct(
        private AnnouncementRepositoryInterface $announcementRepository,
        private FileGetterInterface $fileGetter,
    ) {
    }

    /**
     * @throws AnnouncementFileNotFoundException
     * @throws FileNotExistException
     */
    public function execute(string $announcementId, string $fileId): AnnouncementFileDataDto
    {
        $announcement = $this->announcementRepository->findOneById($announcementId);
        $announcementFile = $announcement?->getFile($fileId);

        if (!$announcementFile) {
            throw new AnnouncementFileNotFoundException("Not found file `$fileId` for announcement `$announcementId`");
        }

        return new AnnouncementFileDataDto(
            $announcementFile->getName(),
            $this->fileGetter->execute($announcementFile->getPath()),
        );
    }
}
