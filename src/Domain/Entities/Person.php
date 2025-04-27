<?php

declare(strict_types=1);

namespace App\Domain\Entities;

/**
 * Ignore: just a test class that emphasizes a more "data separate from procedures" approach
 */
class Person
{
    public function __construct(protected string $firstName, protected string $lastName) {}
}
