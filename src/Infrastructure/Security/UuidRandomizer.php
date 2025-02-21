<?php

namespace App\Infrastructure\Security;

use App\Domain\Interfaces\RandomInterface;
use Symfony\Component\Uid\Uuid;

/** @SuppressUnused */
class UuidRandomizer implements RandomInterface
{
    public function generate(): string
    {
        $first = Uuid::v7();
        $second = Uuid::v7();
        return "$first-$second";
    }
}
