<?php

declare(strict_types=1);

namespace App\Domain\Interfaces;

interface RandomInterface
{
    public function generate(): string;
}
