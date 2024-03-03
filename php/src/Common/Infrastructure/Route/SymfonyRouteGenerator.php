<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Route;

use App\Common\Domain\Route\RouteGeneratorInterface;
use App\Common\Domain\Route\RouteTypeEnum;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class SymfonyRouteGenerator implements RouteGeneratorInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function execute(string $routeName, RouteTypeEnum $routeTypeEnum, array $params = []): string
    {
        return $this->urlGenerator->generate(
            $routeName,
            $params,
            $this->mapRouteTypeToSymfonyRouteType($routeTypeEnum),
        );
    }

    private function mapRouteTypeToSymfonyRouteType(RouteTypeEnum $routeTypeEnum): int
    {
        return match ($routeTypeEnum) {
            RouteTypeEnum::RELATIVE_PATH => UrlGeneratorInterface::RELATIVE_PATH,
            RouteTypeEnum::ABSOLUTE_PATH => UrlGeneratorInterface::ABSOLUTE_PATH,
            RouteTypeEnum::ABSOLUTE_URL => UrlGeneratorInterface::ABSOLUTE_URL,
            default => UrlGeneratorInterface::ABSOLUTE_URL,
        };
    }
}
