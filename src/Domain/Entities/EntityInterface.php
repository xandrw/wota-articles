<?php

namespace App\Domain\Entities;

use App\Domain\Events\DomainEventInterface;

interface EntityInterface
{
    /**
     * @return array<DomainEventInterface>
     */
    public function getEvents(): array;

    public function addEvent(DomainEventInterface $event): self;

    public function clearEvents(): self;
}
