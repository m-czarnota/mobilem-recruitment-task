<?php

declare(strict_types=1);

namespace App\Common\Domain\Pagination;

class PaginationListDataDto
{
    public PaginationDataDto $paginationDataDto;

    public function __construct(
        public array $records,
        int $totalRecords,
        int $currentPage,
        int $pageSize,
    ) {
        $totalPages = intval(ceil($totalRecords / $pageSize));

        $previousPage = $currentPage > 1 ? $currentPage - 1 : null;
        if ($previousPage !== null && $previousPage > $totalPages) {
            $previousPage = $totalPages;
        }
        $nextPage = $currentPage < $totalPages ? $currentPage + 1 : null;

        $this->paginationDataDto = new PaginationDataDto(
            $totalRecords,
            $currentPage,
            $totalPages,
            $nextPage,
            $previousPage,
        );
    }
}
