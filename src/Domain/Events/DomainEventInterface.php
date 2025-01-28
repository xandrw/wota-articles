<?php

namespace App\Domain\Events;

use App\Domain\Entities\EntityInterface;

interface DomainEventInterface
{
    public function getEntity(): ?EntityInterface;

    public function setEntity(?EntityInterface $entity): self;
}
