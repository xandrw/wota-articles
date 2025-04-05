<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use RuntimeException;
use Throwable;

class UnauthorizedException extends RuntimeException implements ApplicationExceptionInterface
{
    public function __construct(int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct(code: $code, previous: $previous);
    }
}
