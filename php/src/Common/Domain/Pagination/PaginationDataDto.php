<?php

declare(strict_types=1);

namespace App\Common\Domain\Pagination;

class PaginationDataDto
{
    public function __construct(
        public int $totalRecords,
        public int $currentPage,
        public int $totalPages,
        public ?int $nextPage = null,
        public ?int $prevPage = null,
    ) {
    }
}
