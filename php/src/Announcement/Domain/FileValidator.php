<?php

declare(strict_types=1);

namespace App\Announcement\Domain;

class FileValidator
{
    /**
     * size in bytes.
     */
    public const int MAX_SIZE = 2097152;
    public const string IMAGE_MIME_TYPE_PATTERN = 'image/';

    public static function validate(int $size, string $mimeType): array
    {
        $errors = [];

        $maxSize = self::MAX_SIZE;
        if ($size > $maxSize) {
            $maxSizeInMb = self::getSizeAsMb(self::MAX_SIZE);
            $sizeInMb = self::getSizeAsMb($size);
            $errors['size'] = "File is too large, allowed size: `$maxSizeInMb` MB, current size: `$sizeInMb` MB";
        }

        if (!str_starts_with($mimeType, self::IMAGE_MIME_TYPE_PATTERN)) {
            $errors['mimeType'] = 'File is not image';
        }

        return $errors;
    }

    public static function getSizeAsMb(int $size): float
    {
        return round($size / 1024 / 1024, 2);
    }
}
