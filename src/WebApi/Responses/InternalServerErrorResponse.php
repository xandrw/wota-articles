<?php

namespace App\WebApi\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InternalServerErrorResponse extends JsonResponse
{
    public function __construct(
        string $message = 'Internal Error',
        array $headers = []
    )
    {
        $data = ['message' => $message];

        parent::__construct(
            $data,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $headers
        );
    }
}
