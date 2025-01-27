<?php

namespace App\Domain;

use InvalidArgumentException;

abstract class Contract
{
    public static function requires(bool $condition, string $message): void
    {
        if ($condition === false) throw new InvalidArgumentException($message);
    }
}
