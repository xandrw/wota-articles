<?php

namespace App\Presentation\Api\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UnprocessableEntityResponse extends JsonResponse
{
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'Validation failed',
        array $headers = [],
    )
    {
        $data = ['message' => $message, 'errors' => []];

        if ($violations->count() > 0) {
            foreach ($violations as $violation) {
                $data['errors'][$violation->getPropertyPath()] = [
                    ...$data['errors'][$violation->getPropertyPath()] ?? [],
                    $violation->getMessage(),
                ];
            }
        }

        parent::__construct($data, Response::HTTP_UNPROCESSABLE_ENTITY, $headers);
    }
}
