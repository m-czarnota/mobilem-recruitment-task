<?php

declare(strict_types=1);

namespace App\Announcement\Application\GetAnnouncement;

use App\Announcement\Application\AddAnnouncement\Response\AnnouncementToResponseDtoMapper;
use App\Announcement\Domain\AnnouncementNotFoundException;
use App\Announcement\Domain\GetAnnouncementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/announcements/{announcementId}', requirements: ['announcementId' => '\S+'], methods: Request::METHOD_GET)]
class GetAnnouncementController extends AbstractController
{
    public function __construct(
        private readonly GetAnnouncementService $service,
        private readonly AnnouncementToResponseDtoMapper $announcementToResponseDtoMapper,
    ) {
    }

    public function __invoke(string $announcementId): JsonResponse
    {
        try {
            $announcement = $this->service->execute($announcementId);
        } catch (AnnouncementNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->announcementToResponseDtoMapper->execute($announcement));
    }
}
