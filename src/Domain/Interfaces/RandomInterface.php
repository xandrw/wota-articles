<?php

namespace App\Domain\Interfaces;

interface RandomInterface
{
    public function generate(): string;
}
