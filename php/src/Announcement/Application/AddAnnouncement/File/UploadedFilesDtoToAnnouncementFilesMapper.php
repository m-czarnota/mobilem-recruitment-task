<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement\File;

use App\Announcement\Domain\AnnouncementFile;
use App\Announcement\Domain\AnnouncementFileNotValidException;
use App\Announcement\Domain\FileValidator;

readonly class UploadedFilesDtoToAnnouncementFilesMapper
{
    /**
     * @return array<int, AnnouncementFile>
     *
     * @throws AnnouncementFileNotValidException
     */
    public function execute(UploadedFileDto ...$uploadedFilesDto): array
    {
        $errors = [];
        $isError = false;
        $files = [];

        foreach ($uploadedFilesDto as $uploadedFileDto) {
            $fileErrors = FileValidator::validate(
                $uploadedFileDto->size,
                $uploadedFileDto->mimeType,
            );
            if (!empty($fileErrors)) {
                $isError = true;
                $errors[] = $fileErrors;

                continue;
            }

            try {
                $clientFile = new AnnouncementFile(
                    null,
                    $uploadedFileDto->clientName,
                    $uploadedFileDto->realPath,
                );
            } catch (AnnouncementFileNotValidException $exception) {
                $isError = true;
                $errors[] = json_decode($exception->getMessage());

                continue;
            }

            $files[] = $clientFile;
            $errors[] = [];
        }

        if ($isError) {
            throw new AnnouncementFileNotValidException(json_encode(['files' => $errors]));
        }

        return $files;
    }
}
