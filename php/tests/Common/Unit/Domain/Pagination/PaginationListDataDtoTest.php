<?php

declare(strict_types=1);

namespace App\Tests\Common\Unit\Domain\Pagination;

use App\Common\Domain\Pagination\PaginationListDataDto;
use PHPUnit\Framework\TestCase;

class PaginationListDataDtoTest extends TestCase
{
    /**
     * @dataProvider constructDataProvider
     */
    public function testConstruct(
        int $totalRecords,
        int $currentPage,
        int $pageSize,
        int $exceptedTotalPages,
        ?int $exceptedNextPage,
        ?int $exceptedPrevPage,
    ): void {
        $paginationListDataDto = new PaginationListDataDto(
            [],
            $totalRecords,
            $currentPage,
            $pageSize,
        );
        $paginationDataDto = $paginationListDataDto->paginationDataDto;

        self::assertSame($exceptedTotalPages, $paginationDataDto->totalPages);
        self::assertSame($exceptedNextPage, $paginationDataDto->nextPage);
        self::assertSame($exceptedPrevPage, $paginationDataDto->prevPage);
    }

    public function constructDataProvider(): array
    {
        return [
            'last page of 3 pages' => [
                'totalRecords' => 8,
                'currentPage' => 3,
                'pageSize' => 3,
                'exceptedTotalPages' => 3,
                'exceptedNextPage' => null,
                'exceptedPrevPage' => 2,
            ],
            'first page of 3 pages' => [
                'totalRecords' => 8,
                'currentPage' => 1,
                'pageSize' => 3,
                'exceptedTotalPages' => 3,
                'exceptedNextPage' => 2,
                'exceptedPrevPage' => null,
            ],
            '5th page of 3 pages' => [
                'totalRecords' => 8,
                'currentPage' => 5,
                'pageSize' => 3,
                'exceptedTotalPages' => 3,
                'exceptedNextPage' => null,
                'exceptedPrevPage' => 3,
            ],
            'only 1 page, current page 1' => [
                'totalRecords' => 8,
                'currentPage' => 1,
                'pageSize' => 10,
                'exceptedTotalPages' => 1,
                'exceptedNextPage' => null,
                'exceptedPrevPage' => null,
            ],
        ];
    }
}
