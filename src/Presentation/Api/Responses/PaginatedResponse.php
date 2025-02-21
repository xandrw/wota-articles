<?php

namespace App\Presentation\Api\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PaginatedResponse extends JsonResponse
{
    public function __construct(
        array $data,
        int $status = Response::HTTP_OK,
        array $headers = [],
    )
    {
        parent::__construct($data, $status, $headers);
    }
}
