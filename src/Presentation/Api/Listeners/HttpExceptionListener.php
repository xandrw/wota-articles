<?php

declare(strict_types=1);

namespace App\Presentation\Api\Listeners;

use App\Presentation\Api\Responses\UnprocessableEntityResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/** @SuppressUnused */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
class HttpExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface === false) {
            return;
        }

        if ($exception instanceof ValidationFailedException) {
            $event->setResponse(new UnprocessableEntityResponse($exception->getViolations()));
            return;
        }

        $previousException = $exception->getPrevious();

        if ($previousException instanceof ValidationFailedException) {
            $event->setResponse(new UnprocessableEntityResponse(
                violations: $previousException->getViolations(),
                headers: $exception->getHeaders(),
            ));
            return;
        }

        if (empty($exception->getMessage())) {
            $event->setResponse(new Response(null, $exception->getStatusCode(), $exception->getHeaders()));
            return;
        }

        $event->setResponse(new JsonResponse(
            ['message' => $exception->getMessage()],
            $exception->getStatusCode(),
            $exception->getHeaders(),
        ));
    }
}
