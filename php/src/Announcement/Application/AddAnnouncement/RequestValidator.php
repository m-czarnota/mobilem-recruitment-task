<?php

declare(strict_types=1);

namespace App\Announcement\Application\AddAnnouncement;

use Symfony\Component\HttpFoundation\RequestStack;

readonly class RequestValidator
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function execute(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $errors = [];

        if ($request->get('title') === null) {
            $errors['title'] = 'Missing `title` parameter';
        }
        if ($request->get('description') === null) {
            $errors['description'] = 'Missing `description` parameter';
        }
        if ($request->get('cost') === null) {
            $errors['cost'] = 'Missing `cost` parameter';
        }

        return $errors;
    }
}
