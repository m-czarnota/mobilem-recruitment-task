<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement;

use App\Announcement\Application\AddAnnouncement\File\RequestToUploadedFilesDtoMapper;
use App\Announcement\Application\AddAnnouncement\File\UploadedFilesDtoToAnnouncementFilesMapper;
use App\Announcement\Application\AddAnnouncement\Response\AnnouncementToResponseDtoMapper;
use App\Announcement\Domain\AddAnnouncementService;
use App\Announcement\Domain\AnnouncementFileNotValidException;
use App\Announcement\Domain\AnnouncementFilesCountExceededException;
use App\Announcement\Domain\AnnouncementNotValidException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/announcements', methods: Request::METHOD_POST)]
class AddAnnouncementController extends AbstractController
{
    public function __construct(
        private readonly RequestValidator $requestValidator,
        private readonly RequestToAnnouncementMapper $requestToAnnouncementMapper,
        private readonly RequestToUploadedFilesDtoMapper $requestToUploadedFilesDtoMapper,
        private readonly UploadedFilesDtoToAnnouncementFilesMapper $uploadedFilesDtoToAnnouncementFilesMapper,
        private readonly EntityManagerInterface $entityManager,
        private readonly AddAnnouncementService $service,
        private readonly AnnouncementToResponseDtoMapper $announcementToResponseDtoMapper,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $errors = $this->requestValidator->execute();
        if (!empty($errors)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        try {
            $client = $this->requestToAnnouncementMapper->execute();
        } catch (AnnouncementNotValidException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_NOT_ACCEPTABLE, json: true);
        }

        $uploadedFilesDtoMapper = $this->requestToUploadedFilesDtoMapper->execute();

        try {
            $files = $this->uploadedFilesDtoToAnnouncementFilesMapper->execute(...$uploadedFilesDtoMapper);
        } catch (AnnouncementFileNotValidException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_ACCEPTABLE, json: true);
        }

        try {
            foreach ($files as $file) {
                $client->addFile($file);
            }
        } catch (AnnouncementFilesCountExceededException $exception) {
            return new JsonResponse(['generalError' => $exception->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }

        $client = $this->service->execute($client);

        $this->entityManager->flush();

        return new JsonResponse($this->announcementToResponseDtoMapper->execute($client), Response::HTTP_CREATED);
    }
}
