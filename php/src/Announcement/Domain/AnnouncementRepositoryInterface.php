<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

interface AnnouncementRepositoryInterface
{
    public function add(Announcement $announcement): void;

    public function findOneById(string $id): ?Announcement;

    public function findPage(int $pageNumber, int $pageSize): array;

    public function findCount(): int;
}
