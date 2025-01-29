<?php

namespace App\Domain\Entities;

use App\Domain\Events\DomainEventInterface;

interface EntityInterface
{
    /**
     * The events will be published when Doctrine flush is called
     *
     * @return array<DomainEventInterface>
     */
    public function getEvents(): array;

    /**
     * Call inside or outside Domain entities
     */
    public function addEvent(DomainEventInterface $event): self;

    /**
     * Will get called at the end of Doctrine flush
     */
    public function clearEvents(): self;
}
