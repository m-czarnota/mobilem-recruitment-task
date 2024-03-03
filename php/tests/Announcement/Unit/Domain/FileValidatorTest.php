<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Unit\Domain;

use App\Announcement\Domain\FileValidator;
use PHPUnit\Framework\TestCase;

class FileValidatorTest extends TestCase
{
    /**
     * @dataProvider executeDataProvider
     */
    public function testExecute(int $size, string $mimeType, array $exceptedErrors): void
    {
        $errors = FileValidator::validate($size, $mimeType);
        self::assertCount(count($exceptedErrors), $errors);

        foreach ($exceptedErrors as $errorName => $errorMessage) {
            self::assertArrayHasKey($errorName, $errors);
            self::assertEquals($errors[$errorName], $errorMessage);
        }
    }

    public static function executeDataProvider(): array
    {
        $maxSize = FileValidator::getSizeAsMb(FileValidator::MAX_SIZE);

        return [
            'no errors' => [
                'size' => 80,
                'mimeType' => 'image/jpeg',
                'exceptedErrors' => [],
            ],
            'too large file and wrong mime type' => [
                'size' => 423543623543,
                'mimeType' => 'application/pdf',
                'exceptedErrors' => [
                    'size' => "File is too large, allowed size: `$maxSize` MB, current size: `403922.68` MB",
                    'mimeType' => 'File is not image',
                ],
            ],
            'non-existed kind of image' => [
                'size' => 24325,
                'mimeType' => 'image/non-existed-image-type',
                'exceptedErrors' => [],
            ],
        ];
    }
}
