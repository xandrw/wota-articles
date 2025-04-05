<?php

declare(strict_types=1);

namespace App\Presentation\Api\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InternalServerErrorResponse extends JsonResponse
{
    public function __construct(string $message = 'Internal Error', array $headers = [])
    {
        parent::__construct(['message' => $message], Response::HTTP_INTERNAL_SERVER_ERROR, $headers);
    }
}
