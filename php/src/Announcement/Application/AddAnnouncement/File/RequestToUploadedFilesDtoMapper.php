<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class RequestToUploadedFilesDtoMapper
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @return array<int, UploadedFileDto>
     */
    public function execute(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $fileBag = $request->files;
        $uploadedFiles = $fileBag->get('files', []);

        return array_map(fn (UploadedFile $file) => new UploadedFileDto(
            $file->getClientOriginalName(),
            mime_content_type($file->getRealPath()),
            $file->getSize(),
            $file->getRealPath(),
        ), $uploadedFiles);
    }
}
