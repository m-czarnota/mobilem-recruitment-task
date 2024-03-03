<?php

declare(strict_types=1);

namespace App\Tests\Common\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Then;
use Behat\Step\When;
use PHPUnit\Framework\Assert;

readonly class CommonContext implements Context
{
    public function __construct(
        private MobilemKernelBrowser $browser
    ) {
    }

    #[When('I open :requestType page :url')]
    public function iOpenPage(string $requestType, string $url): void
    {
        $this->browser->json($requestType, $url);
    }

    #[When('I open :requestType page :url with')]
    public function iOpenPageWith(string $requestType, string $url, PyStringNode $request): void
    {
        $this->browser->json($requestType, $url, trim($request->getRaw()));
    }

    #[When('I open :requestType page :url with form data')]
    public function iOpenPageWithFormData(string $requestType, string $url, PyStringNode $request): void
    {
        $this->browser->jsonFormData($requestType, $url, trim($request->getRaw()));
    }

    #[When('I open :requestType page :url with form data with files')]
    public function iOpenPageWithFormDataWithFiles(string $requestType, string $url, PyStringNode $request): void
    {
        $this->browser->jsonFormDataWithFiles($requestType, $url, trim($request->getRaw()));
    }

    #[Then('the response with code :code should be received')]
    public function theResponseWithCodeShouldBeReceived(int $statusCode): void
    {
        Assert::assertEquals($this->browser->getLastResponseCode(), $statusCode);
    }

    #[Then('the response with code :code should not be received')]
    public function theResponseWithCodeShouldNotBeReceived(int $statusCode): void
    {
        Assert::assertNotEquals($this->browser->getLastResponseCode(), $statusCode);
    }
}
