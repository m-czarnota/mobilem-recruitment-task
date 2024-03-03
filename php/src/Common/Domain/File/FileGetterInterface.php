<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

use SplFileInfo;

interface FileGetterInterface
{
    /**
     * @throws FileNotExistException
     */
    public function execute(string $filePath): SplFileInfo;
}
