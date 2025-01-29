<?php

namespace App\Presentation\Api\Responses;

use Symfony\Component\HttpFoundation\Response;

class NoContentResponse extends Response
{
    public function __construct(array $headers = [])
    {
        parent::__construct(null, Response::HTTP_NO_CONTENT, $headers);
    }
}
