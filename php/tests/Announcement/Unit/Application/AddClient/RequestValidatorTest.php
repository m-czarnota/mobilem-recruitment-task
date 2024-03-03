<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Unit\Application\AddClient;

use App\Announcement\Application\AddAnnouncement\RequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestValidatorTest extends TestCase
{
    private readonly RequestStack $requestStack;
    private readonly RequestValidator $service;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->service = new RequestValidator(
            $this->requestStack,
        );
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecute(array $requestContent, array $expectedErrors): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->method('get')
            ->willReturnMap(array_map(
                fn (string $val, string $key) => [$key, null, $val],
                $requestContent,
                array_keys($requestContent)
            ));

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $errors = $this->service->execute();
        self::assertCount(count($expectedErrors), $errors);

        foreach ($expectedErrors as $exceptedErrorName => $exceptedErrorMessage) {
            self::assertArrayHasKey($exceptedErrorName, $errors);
            self::assertEquals($exceptedErrorMessage, $errors[$exceptedErrorName]);
        }
    }

    public static function executeDataProvider(): array
    {
        return [
            'no request content' => [
                'requestContent' => [],
                'exceptedErrors' => [
                    'title' => 'Missing `title` parameter',
                    'description' => 'Missing `description` parameter',
                    'cost' => 'Missing `cost` parameter',
                ],
            ],
            'additional not necessary fields' => [
                'requestContent' => [
                    'id' => 'm',
                    'next field' => 'random value',
                ],
                'exceptedErrors' => [
                    'title' => 'Missing `title` parameter',
                    'description' => 'Missing `description` parameter',
                    'cost' => 'Missing `cost` parameter',
                ],
            ],
            'all required fields' => [
                'requestContent' => [
                    'title' => 'I will sell Opel',
                    'description' => 'Great occasion! Only 100 onions!',
                    'cost' => 100,
                ],
                'exceptedErrors' => [],
            ],
        ];
    }
}
