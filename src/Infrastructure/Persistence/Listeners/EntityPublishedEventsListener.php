<?php

namespace App\Infrastructure\Persistence\Listeners;

use App\Domain\Entities\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/** @SuppressUnused */
#[AsDoctrineListener(Events::onFlush)]
readonly class EntityPublishedEventsListener
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    /** @SuppressUnused */
    public function onFlush(OnFlushEventArgs $arguments): void
    {
        $objectManager = $arguments->getObjectManager();
        $unitOfWork = $objectManager->getUnitOfWork();

        $scheduledEntities = [
            ...$unitOfWork->getScheduledEntityInsertions(),
            ...$unitOfWork->getScheduledEntityUpdates(),
            ...$unitOfWork->getScheduledEntityDeletions(),
        ];

        foreach ($scheduledEntities as $scheduledEntity) {
            if ($scheduledEntity instanceof EntityInterface === false) {
                continue;
            }

            foreach ($scheduledEntity->getEvents() as $event) {
                $this->dispatcher->dispatch($event);
            }

            $scheduledEntity->clearEvents();
        }
    }
}
