<?php

declare(strict_types=1);

namespace App\Domain\Interfaces;

interface RandomizerInterface
{
    public function generate(): string;
}
