<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Unit\Domain;

use App\Announcement\Domain\Announcement;
use App\Announcement\Domain\AnnouncementNotValidException;
use PHPUnit\Framework\TestCase;

class AnnouncementTest extends TestCase
{
    /**
     * @throws AnnouncementNotValidException
     *
     * @dataProvider __constructValidationsDataProvider
     */
    public function testConstructValidations(array $announcementData, string $exception, string $exceptionMessage): void
    {
        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        new Announcement(
            $announcementData['id'],
            $announcementData['title'],
            $announcementData['description'],
            $announcementData['cost'],
        );
    }

    public static function __constructValidationsDataProvider(): array
    {
        return [
            'empty fields' => [
                'announcementData' => [
                    'id' => '',
                    'title' => '',
                    'description' => '',
                    'cost' => 0,
                ],
                'exception' => AnnouncementNotValidException::class,
                'exceptionMessage' => json_encode([
                    'id' => 'Id cannot be empty',
                    'title' => 'Title cannot be empty',
                    'description' => 'Description cannot be empty',
                ]),
            ],
            'too long fields' => [
                'announcementData' => [
                    'id' => 'too long too long too long too long too long too long too long too long too long too long',
                    'title' => 'too long too long too long too long too long too long too long too long too long too long',
                    'description' => 'Great deal! Only 100 onions!',
                    'cost' => 100,
                ],
                'exception' => AnnouncementNotValidException::class,
                'exceptionMessage' => json_encode([
                    'id' => 'Id cannot be longer than 50 characters',
                    'title' => 'Title cannot be longer than 80 characters',
                ]),
            ],
            'negative cost' => [
                'announcementData' => [
                    'id' => '1',
                    'title' => 'I will sell Opel',
                    'description' => 'Great occasion!',
                    'cost' => -10,
                ],
                'exception' => AnnouncementNotValidException::class,
                'exceptionMessage' => json_encode([
                    'cost' => 'Cost cannot be negative',
                ]),
            ],
        ];
    }
}
