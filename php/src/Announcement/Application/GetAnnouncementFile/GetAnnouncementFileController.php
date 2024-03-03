<?php

declare(strict_types=1);

namespace App\Announcement\Application\GetAnnouncementFile;

use App\Announcement\Domain\AnnouncementFileNotFoundException;
use App\Announcement\Domain\GetAnnouncementFileService;
use App\Common\Domain\File\FileNotExistException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/announcements-files/{announcementId}/{fileId}',
    name: 'api:v1:get_announcement_file',
    requirements: ['announcementId' => '\S+', 'fileId' => '\S+'],
    methods: Request::METHOD_GET
)]
class GetAnnouncementFileController extends AbstractController
{
    public function __construct(
        private readonly GetAnnouncementFileService $service,
    ) {
    }

    public function __invoke(string $announcementId, string $fileId): BinaryFileResponse|Response
    {
        try {
            $fileDataDto = $this->service->execute($announcementId, $fileId);
        } catch (AnnouncementFileNotFoundException|FileNotExistException $e) {
            return new Response($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        $fileInfo = $fileDataDto->fileInfo;

        $response = new BinaryFileResponse($fileInfo);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileDataDto->originalFilename);
        $response->headers->set('Content-Type', mime_content_type($fileInfo->getRealPath()));

        return $response;
    }
}
