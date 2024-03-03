<?php

declare(strict_types=1);

namespace App\Tests\Common\Unit\Infrastructure;

use App\Common\Domain\File\FileNotExistException;
use App\Common\Infrastructure\File\LocalFileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocalFileUploaderTest extends TestCase
{
    private readonly ParameterBagInterface $parameterBag;
    private readonly LocalFileUploader $service;

    protected function setUp(): void
    {
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->service = new LocalFileUploader(
            $this->parameterBag,
        );
    }

    /**
     * @throws FileNotExistException
     *
     * @dataProvider executeDataProvider
     */
    public function testExecute(string $uploadLocalDir, string $projectDir, string $filePath): void
    {
        $this->parameterBag
            ->method('get')
            ->willReturnMap([
                ['project_dir', $projectDir],
                ['upload.local.dir', $uploadLocalDir],
            ]);

        $fileToUpload = "$projectDir/$filePath";
        $uploadedFilePath = $this->service->execute($fileToUpload);

        $realPathToUploadedFile = "$projectDir/$uploadedFilePath";
        self::assertFileExists($realPathToUploadedFile);

        unlink($realPathToUploadedFile);
    }

    public function executeDataProvider(): array
    {
        return [
            'upload teddy bear' => [
                'uploadLocalDir' => '/upload',
                'projectDir' => '/var/www/html',
                'filePath' => 'tests/Common/File/teddy-bear.jpg',
            ],
        ];
    }

    /**
     * @throws FileNotExistException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(
        string $uploadLocalDir,
        string $projectDir,
        string $filePath,
        string $exception,
        string $exceptionMessage,
    ): void {
        $this->parameterBag
            ->method('get')
            ->willReturnMap([
                ['project_dir', $projectDir],
                ['upload.local.dir', $uploadLocalDir],
            ]);

        $fileToUpload = "$projectDir/$filePath";

        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute($fileToUpload);
    }

    public static function executeValidationsDataProvider(): array
    {
        return [
            'non existed file to copy' => [
                'uploadLocalDir' => '/upload',
                'projectDir' => '/var/www/html',
                'filePath' => 'non-existed-file',
                'exception' => FileNotExistException::class,
                'exceptionMessage' => 'File `/var/www/html/non-existed-file` does not exist',
            ],
        ];
    }
}
