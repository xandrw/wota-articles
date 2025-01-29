<?php

namespace App\Application\Features\Auth;

use App\Domain\Entities\Users\Events\PasswordChangedEvent;
use App\Domain\Entities\Users\Events\UserLoggedInEvent;
use App\Domain\Entities\Users\User;
use App\Domain\Events\DomainEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/** @SuppressUnused */
readonly class InvalidateTokensSubscriber implements EventSubscriberInterface
{
    public function __construct(private DeleteAccessTokensInvoker $deleteTokensInvoker)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserLoggedInEvent::class => ['invalidate', 0],
            PasswordChangedEvent::class => ['invalidate', 0],
        ];
    }

    public function invalidate(DomainEventInterface $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof User === false) {
            return;
        }

        ($this->deleteTokensInvoker)($entity);
    }
}
