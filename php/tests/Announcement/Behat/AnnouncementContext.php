<?php

declare(strict_types=1);

namespace App\Tests\Announcement\Behat;

use App\Announcement\Domain\AnnouncementFileNotValidException;
use App\Announcement\Domain\AnnouncementFilesCountExceededException;
use App\Announcement\Domain\AnnouncementNotValidException;
use App\Announcement\Domain\AnnouncementRepositoryInterface;
use App\Tests\Announcement\Stub\AnnouncementStub;
use App\Tests\Common\Behat\MobilemKernelBrowser;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Given;
use Behat\Step\Then;
use PHPUnit\Framework\Assert;

readonly class AnnouncementContext implements Context
{
    public function __construct(
        private MobilemKernelBrowser $browser,
        private readonly AnnouncementRepositoryInterface $announcementRepository,
    ) {
    }

    #[Then('the response should looks like')]
    public function theResponseShouldLooksLike(PyStringNode $dummyResponse): void
    {
        $dummyResponseFields = json_decode(trim($dummyResponse->getRaw()), true);
        $response = $this->browser->getLastResponseContentAsArray();

        $this->checkIfResponseLooksLikeDummyResponse($response, $dummyResponseFields);
    }

    #[Then('the response should contains message :message')]
    public function theResponseShouldContainsMessage(string $message): void
    {
        $response = trim($this->browser->getLastResponseContent());
        $responseMessage = json_decode($response);

        Assert::assertEquals($message, $responseMessage);
    }

    /**
     * @throws AnnouncementNotValidException
     * @throws AnnouncementFilesCountExceededException
     * @throws AnnouncementFileNotValidException
     */
    #[Given('there exist an announcement like')]
    public function thereExistAnAnnouncementLike(PyStringNode $announcementContent): void
    {
        $announcementData = json_decode(trim($announcementContent->getRaw()), true);

        $existedAnnouncement = $this->announcementRepository->findOneById($announcementData['id'] ?? '');
        if ($existedAnnouncement) {
            return;
        }

        $client = AnnouncementStub::createFromArrayData($announcementData);
        $this->announcementRepository->add($client);
    }

    private function checkIfResponseLooksLikeDummyResponse(array $response, array $dummyResponse): void
    {
        $specialNonExistedDataInResponse = '!not exists@';

        foreach ($dummyResponse as $dummyResponseIndex => $dummyResponseData) {
            $responseData = array_key_exists($dummyResponseIndex, $response)
                ? $response[$dummyResponseIndex]
                : $specialNonExistedDataInResponse;

            if (is_bool($responseData)) {
                Assert::assertEquals($specialNonExistedDataInResponse, $responseData);
            } else {
                Assert::assertNotEquals($specialNonExistedDataInResponse, $responseData);
            }
            Assert::assertEquals(gettype($dummyResponseData), gettype($responseData));

            if (is_array($dummyResponseData)) {
                $this->checkIfResponseLooksLikeDummyResponse($responseData, $dummyResponseData);
            }
        }
    }
}
