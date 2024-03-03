<?php

declare(strict_types=1);

namespace App\Common\Domain\Route;

interface RouteGeneratorInterface
{
    public function execute(string $routeName, RouteTypeEnum $routeTypeEnum, array $params = []): string;
}
