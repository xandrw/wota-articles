<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Domain\Entities\Users\Events\UserCredentialsChanged;
use App\Domain\Entities\Users\Events\UserLoggedInEvent;
use App\Domain\Entities\Users\Events\UserLoggedOutEvent;
use App\Domain\Entities\Users\User;
use App\Domain\Events\DomainEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/** @SuppressUnused */
readonly class InvalidateTokensSubscriber implements EventSubscriberInterface
{
    public function __construct(private DeleteUserTokensInvoker $deleteUserTokensInvoker) {}

    public static function getSubscribedEvents(): array
    {
        return [
            UserLoggedInEvent::class => ['deleteUserTokens'],
            UserLoggedOutEvent::class => ['deleteUserTokens'],
            UserCredentialsChanged::class => ['deleteUserTokens'],
        ];
    }

    public function deleteUserTokens(DomainEventInterface $event): void
    {
        $entity = $event->getEntity();

        if ($entity instanceof User === false) {
            return;
        }

        $this->deleteUserTokensInvoker->__invoke($entity);
    }
}
