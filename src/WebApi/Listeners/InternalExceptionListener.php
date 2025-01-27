<?php

namespace App\WebApi\Listeners;

use App\Application\Exceptions\ApplicationExceptionInterface;
use App\WebApi\Responses\InternalServerErrorResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/** @SuppressUnused */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
readonly class InternalExceptionListener
{
    public function __construct(private string $appEnv)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApplicationExceptionInterface || $exception instanceof HttpExceptionInterface) {
            return;
        }

        if ($this->appEnv === 'prod') {
            $event->setResponse(new InternalServerErrorResponse());
            return;
        }

        $event->setResponse(new InternalServerErrorResponse($exception->getMessage()));
    }
}
