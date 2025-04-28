<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Interfaces\RandomizerInterface;
use Symfony\Component\Uid\Uuid;

/** @SuppressUnused */
class UuidRandomizer implements RandomizerInterface
{
    public function generate(): string
    {
        $first = Uuid::v7();
        $second = Uuid::v7();
        return "$first-$second";
    }
}
