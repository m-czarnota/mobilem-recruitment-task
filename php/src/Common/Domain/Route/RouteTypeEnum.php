<?php

declare(strict_types=1);

namespace App\Common\Domain\Route;

enum RouteTypeEnum: int
{
    case RELATIVE_PATH = 1;
    case ABSOLUTE_PATH = 2;
    case ABSOLUTE_URL = 3;
}
