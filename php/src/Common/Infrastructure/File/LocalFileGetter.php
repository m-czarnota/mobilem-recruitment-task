<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\File;

use App\Common\Domain\File\FileGetterInterface;
use App\Common\Domain\File\FileNotExistException;
use SplFileInfo;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class LocalFileGetter implements FileGetterInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @throws FileNotExistException
     */
    public function execute(string $filePath): SplFileInfo
    {
        $kernelDir = $this->parameterBag->get('project_dir');
        $localFilePath = "$kernelDir/$filePath";

        if (!file_exists($localFilePath)) {
            throw new FileNotExistException("File `$filePath` not exist");
        }

        return new SplFileInfo($localFilePath);
    }
}
