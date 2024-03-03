<?php

declare(strict_types=1);

namespace App\Common\Domain\Pagination;

interface PaginationResponseCreatorInterface
{
    public function execute(PaginationListDataDto $paginationListData, string $route): PaginationResponseInterface;
}
