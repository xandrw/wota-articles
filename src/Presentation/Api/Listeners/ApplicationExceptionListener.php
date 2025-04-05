<?php

declare(strict_types=1);

namespace App\Presentation\Api\Listeners;

use App\Application\Exceptions\ApplicationExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

        if (empty($exception->getMessage())) {
            $event->setResponse(new Response(null, $exception->getCode()));
            return;
        }

        $event->setResponse(new JsonResponse(['message' => $exception->getMessage()], $exception->getCode()));
    }
}
