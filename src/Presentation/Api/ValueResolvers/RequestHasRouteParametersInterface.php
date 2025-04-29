<?php

declare(strict_types=1);

namespace App\Presentation\Api\ValueResolvers;

interface RequestHasRouteParametersInterface
{
    public static function getRouteParameterKeys(): array;
}
