<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entities\EntityInterface;

trait EventHasEntityTrait
{
    private ?EntityInterface $entity;

    public function getEntity(): ?EntityInterface
    {
        return $this->entity;
    }

    public function setEntity(?EntityInterface $entity): self
    {
        $this->entity = $entity;
        return $this;
    }
}
