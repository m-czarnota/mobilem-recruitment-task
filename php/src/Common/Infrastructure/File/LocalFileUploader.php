<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\File;

use App\Common\Domain\File\FileNotExistException;
use App\Common\Domain\File\FileUploaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class LocalFileUploader implements FileUploaderInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @throws FileNotExistException
     */
    public function execute(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new FileNotExistException("File `$filePath` does not exist");
        }

        $uploadDirPath = $this->getUploadDirPath();
        if (!is_dir($uploadDirPath)) {
            mkdir($uploadDirPath, recursive: true);
        }

        $newFileName = time() . '_' . bin2hex(openssl_random_pseudo_bytes(20));
        $newFilePath = "$uploadDirPath/$newFileName";

        copy($filePath, $newFilePath);

        return "{$this->getUploadDir()}/$newFileName";
    }

    private function getUploadDirPath(): string
    {
        $projectDir = $this->parameterBag->get('project_dir');
        $localUploadDir = $this->getUploadDir();

        return "$projectDir/$localUploadDir";
    }

    private function getUploadDir(): string
    {
        return $this->parameterBag->get('upload.local.dir');
    }
}
