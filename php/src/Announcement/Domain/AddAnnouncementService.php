<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

use App\Common\Domain\File\FileNotExistException;
use App\Common\Domain\File\FileUploaderInterface;

readonly class AddAnnouncementService
{
    public function __construct(
        private AnnouncementRepositoryInterface $announcementRepository,
        private FileUploaderInterface $fileUploader,
    ) {
    }

    /**
     * @throws FileNotExistException
     * @throws AnnouncementFileNotValidException
     */
    public function execute(Announcement $announcement): Announcement
    {
        foreach ($announcement->getFiles() as $announcementFile) {
            $newFilePath = $this->fileUploader->execute($announcementFile->getPath());
            $updatedAnnouncementFile = new AnnouncementFile(
                $announcementFile->id,
                $announcementFile->getName(),
                $newFilePath,
            );

            $announcement->updateFile($updatedAnnouncementFile);
        }

        $this->announcementRepository->add($announcement);

        return $announcement;
    }
}
