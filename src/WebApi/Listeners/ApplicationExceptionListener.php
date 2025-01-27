<?php

namespace App\WebApi\Listeners;

use App\Application\Exceptions\ApplicationExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/** @SuppressUnused */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
class ApplicationExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApplicationExceptionInterface === false) {
            return;
        }

        $event->setResponse(new JsonResponse(['message' => $exception->getMessage()], $exception->getCode()));
    }
}
