<?php

declare(strict_types=1);

namespace App\Domain\Entities\Users\Events;

use App\Domain\Entities\Users\User;
use App\Domain\Events\DomainEventInterface;
use App\Domain\Events\EventHasEntityTrait;

class PasswordChangedEvent implements DomainEventInterface
{
    use EventHasEntityTrait;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }
}
