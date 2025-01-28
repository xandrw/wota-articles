<?php

namespace App\Domain\Validation;

use InvalidArgumentException;

trait ValidationTrait
{
    public final static function requires(bool $condition, string $message): void
    {
        if ($condition === false) throw new InvalidArgumentException($message);
    }
}
