<?php

declare(strict_types=1);

namespace App\Tests\Common\Unit\Infrastructure;

use App\Common\Domain\File\FileNotExistException;
use App\Common\Infrastructure\File\LocalFileGetter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocalFileGetterTest extends TestCase
{
    private readonly ParameterBagInterface $parameterBag;
    private readonly LocalFileGetter $service;

    protected function setUp(): void
    {
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->service = new LocalFileGetter(
            $this->parameterBag,
        );
    }

    /**
     * @throws FileNotExistException
     *
     * @dataProvider executeDataProvider
     */
    public function testExecute(string $filePath, string $projectDir): void
    {
        $this->parameterBag
            ->method('get')
            ->willReturnMap([
                ['project_dir', $projectDir],
            ]);

        $fileInfo = $this->service->execute($filePath);

        $fileNameParts = explode('/', $filePath);
        $fileName = $fileNameParts[count($fileNameParts) - 1];
        self::assertEquals($fileName, $fileInfo->getBasename());
    }

    public static function executeDataProvider(): array
    {
        return [
            'teddy bear' => [
                'filePath' => '/tests/Common/File/teddy-bear.jpg',
                'projectDir' => '/var/www/html',
            ],
        ];
    }

    /**
     * @throws FileNotExistException
     *
     * @dataProvider executeValidationsDataProvider
     */
    public function testExecuteValidations(string $filePath, string $exception, string $exceptionMessage): void
    {
        self::expectException($exception);
        self::expectExceptionMessage($exceptionMessage);

        $this->service->execute($filePath);
    }

    public static function executeValidationsDataProvider(): array
    {
        return [
            'non existed filepath' => [
                'filePath' => 'non-existed-file',
                'exception' => FileNotExistException::class,
                'exceptionMessage' => 'File `non-existed-file` not exist',
            ],
        ];
    }
}
