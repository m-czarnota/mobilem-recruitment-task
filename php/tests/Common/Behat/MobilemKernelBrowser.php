<?php

declare(strict_types=1);

namespace App\Tests\Common\Behat;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class MobilemKernelBrowser
{
    public function __construct(
        private KernelBrowser $browser,
    ) {
    }

    public function json(string $method, string $url, mixed $data = null, array $headers = []): void
    {
        $this->browser->request($method, $url, server: $headers, content: $this->mapContent($data));
    }

    public function jsonFormData(string $method, string $url, mixed $data = null, array $headers = []): void
    {
        $content = $this->mapContent($data);
        $this->browser->request($method, $url, json_decode($content, true), server: $headers);
    }

    public function jsonFormDataWithFiles(string $method, string $url, mixed $data = null, array $headers = []): void
    {
        $content = $this->mapContent($data);
        $decodedContent = json_decode($content, true);

        $files = array_map(
            function (array $fileData) {
                $path = __DIR__ . '/..' . $fileData['path'];
                $name = $fileData['name'] ?? basename($path);

                return new UploadedFile(
                    $path,
                    $name,
                    $fileData['mimeType'] ?? null,
                );
            },
            $decodedContent['files'] ?? [],
        );

        $this->browser->request(
            $method,
            $url,
            $decodedContent,
            ['files' => $files],
            server: $headers
        );
    }

    public function getLastResponseContent(): string|false
    {
        return $this->browser->getResponse()->getContent();
    }

    public function getLastResponseCode(): int
    {
        return $this->browser->getResponse()->getStatusCode();
    }

    public function getLastResponseContentAsArray(): array
    {
        return json_decode($this->getLastResponseContent(), true);
    }

    private function mapContent(mixed $data): string|false|null
    {
        $content = null;

        if (is_array($data) && !empty($data)) {
            $content = json_encode($data);
        }

        if (is_string($data) && mb_strlen($data) > 0) {
            $content = $data;
        }

        return $content;
    }
}
