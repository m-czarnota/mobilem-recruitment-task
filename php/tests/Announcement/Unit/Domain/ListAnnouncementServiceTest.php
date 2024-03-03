<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Unit\Domain;

use App\Announcement\Domain\AnnouncementNotValidException;
use App\Announcement\Domain\AnnouncementRepositoryInterface;
use App\Announcement\Domain\ListAnnouncementService;
use App\Tests\Announcement\Stub\AnnouncementStub;
use PHPUnit\Framework\TestCase;

class ListAnnouncementServiceTest extends TestCase
{
    private readonly AnnouncementRepositoryInterface $announcementRepository;
    private readonly ListAnnouncementService $service;

    protected function setUp(): void
    {
        $this->announcementRepository = $this->createMock(AnnouncementRepositoryInterface::class);
        $this->service = new ListAnnouncementService(
            $this->announcementRepository,
        );
    }

    /**
     * @throws AnnouncementNotValidException
     *
     * @dataProvider executeDataProvider
     */
    public function testExecute(
        int $totalRecords,
        int $paginationPage,
        int $paginationLimit,
        int $exceptedRecordCount,
    ): void {
        $totalPages = intval(ceil($totalRecords / $paginationLimit));
        if ($paginationPage < $totalPages) {
            $recordCountOnPage = $paginationLimit;
        } elseif ($paginationPage === $totalPages) {
            $recordCountOnPage = $totalRecords % $paginationLimit;
        } else {
            $recordCountOnPage = 0;
        }

        $exampleAnnouncements = [];
        for ($i = 0; $i < $recordCountOnPage; ++$i) {
            $exampleAnnouncements[] = AnnouncementStub::createExample();
        }

        $this->announcementRepository
            ->method('findPage')
            ->willReturn($exampleAnnouncements);

        $this->announcementRepository
            ->method('findCount')
            ->willReturn($totalRecords);

        $paginationListDataDto = $this->service->execute($paginationPage, $paginationLimit);
        self::assertCount($exceptedRecordCount, $paginationListDataDto->records);
    }

    public static function executeDataProvider(): array
    {
        return [
            '8 total records, 2nd page, 3 records' => [
                'totalRecords' => 8,
                'paginationPage' => 2,
                'paginationLimit' => 3,
                'exceptedRecordCount' => 3,
            ],
            '8 total records, 3rd page, 2 records' => [
                'totalRecords' => 8,
                'paginationPage' => 3,
                'paginationLimit' => 3,
                'exceptedRecordCount' => 2,
            ],
            '8 total records, 5th page, 0 records' => [
                'totalRecords' => 8,
                'paginationPage' => 5,
                'paginationLimit' => 3,
                'exceptedRecordCount' => 0,
            ],
        ];
    }
}
