<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

readonly class GetAnnouncementService
{
    public function __construct(
        private AnnouncementRepositoryInterface $announcementRepository,
    ) {
    }

    /**
     * @throws AnnouncementNotFoundException
     */
    public function execute(string $announcementId): Announcement
    {
        $announcement = $this->announcementRepository->findOneById($announcementId);
        if (!$announcement) {
            throw new AnnouncementNotFoundException("Announcement $announcementId does not exist");
        }

        return $announcement;
    }
}
