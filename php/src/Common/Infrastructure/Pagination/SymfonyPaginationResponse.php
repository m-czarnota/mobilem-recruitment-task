<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Pagination;

use App\Common\Domain\Pagination\PaginationListDataDto;
use App\Common\Domain\Pagination\PaginationResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SymfonyPaginationResponse extends JsonResponse implements PaginationResponseInterface
{
    public function __construct(PaginationListDataDto $data)
    {
        parent::__construct($data);
    }
}
