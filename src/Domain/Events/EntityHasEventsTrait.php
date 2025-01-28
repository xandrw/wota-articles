<?php

namespace App\Domain\Events;

trait EntityHasEventsTrait
{
    /** @var DomainEventInterface[] */
    private array $events = [];

    public function getEvents(): array
    {
        return $this->events;
    }

    public function addEvent(DomainEventInterface $event): self
    {
        $this->events[] = $event;
        return $this;
    }

    public function clearEvents(): self
    {
        $this->events = [];
        return $this;
    }
}
