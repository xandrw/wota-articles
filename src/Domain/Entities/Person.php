<?php

declare(strict_types=1);

namespace App\Domain\Entities;

/**
 * Ignore: just a test class that emphasizes a more "data separate from procedures" approach
 */
readonly class Person
{
    public function __construct(public string $firstName, public string $lastName) {}
}
