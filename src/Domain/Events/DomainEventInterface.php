<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entities\EntityInterface;

interface DomainEventInterface
{
    public function getEntity(): ?EntityInterface;

    public function setEntity(?EntityInterface $entity): self;
}
