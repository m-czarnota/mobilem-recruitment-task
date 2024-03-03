<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

interface FileUploaderInterface
{
    /**
     * @throws FileNotExistException
     */
    public function execute(string $filePath): string;
}
