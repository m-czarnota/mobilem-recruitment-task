<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement;

use App\Announcement\Domain\Announcement;
use App\Announcement\Domain\AnnouncementNotValidException;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class RequestToAnnouncementMapper
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @throws AnnouncementNotValidException
     */
    public function execute(): Announcement
    {
        $request = $this->requestStack->getCurrentRequest();

        return new Announcement(
            null,
            $request->get('title', ''),
            $request->get('description', ''),
            floatval($request->get('cost', 0)),
        );
    }
}
