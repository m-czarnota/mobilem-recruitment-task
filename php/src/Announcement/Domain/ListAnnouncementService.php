<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

use App\Common\Domain\Pagination\PaginationListDataDto;

readonly class ListAnnouncementService
{
    public function __construct(
        private AnnouncementRepositoryInterface $announcementRepository,
    ) {
    }

    public function execute(int $paginationPage, int $paginationLimit): PaginationListDataDto
    {
        $records = $this->announcementRepository->findPage($paginationPage, $paginationLimit);
        $totalRecords = $this->announcementRepository->findCount();

        return new PaginationListDataDto(
            $records,
            $totalRecords,
            $paginationPage,
            $paginationLimit,
        );
    }
}
