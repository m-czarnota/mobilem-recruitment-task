<?php

declare(strict_types=1);

namespace App\Announcement\Application\ListAnnouncement;

use App\Announcement\Domain\Announcement;
use App\Announcement\Domain\ListAnnouncementService;
use App\Common\Domain\Pagination\PaginationResponseCreatorInterface;
use App\Common\Domain\Route\RouteGeneratorInterface;
use App\Common\Domain\Route\RouteTypeEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/announcements', name: 'api:v1:announcements:list')]
class ListAnnouncementController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ParameterBagInterface $parameterBag,
        private readonly ListAnnouncementService $service,
        private readonly RouteGeneratorInterface $routeGenerator,
        private readonly PaginationResponseCreatorInterface $paginationResponseCreator,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        $paginationPage = (int) $request->get('page', $this->parameterBag->get('pagination.default_page'));
        $paginationLimit = (int) $request->get('limit', $this->parameterBag->get('pagination.default_limit'));

        $paginationListDataDto = $this->service->execute($paginationPage, $paginationLimit);
        $paginationListDataDto->records = array_map(function (Announcement $announcement) {
            $serializedAnnouncement = $announcement->jsonSerialize();
            $announcementId = $announcement->id;

            $serializedAnnouncement['files'] = array_map(function (array $announcementFileSerialized) use ($announcementId) {
                $routeParams = [
                    'announcementId' => $announcementId,
                    'fileId' => $announcementFileSerialized['id'],
                ];
                $announcementFileSerialized['path'] = $this->routeGenerator->execute(
                    'api:v1:get_announcement_file',
                    RouteTypeEnum::ABSOLUTE_URL,
                    $routeParams,
                );

                return $announcementFileSerialized;
            }, $serializedAnnouncement['files']);

            return $serializedAnnouncement;
        }, $paginationListDataDto->records);

        return $this->paginationResponseCreator->execute($paginationListDataDto, 'api:v1:announcements:list');
    }
}
