<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Auth\UpdateEmail;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateEmailRequest
{
    public function __construct(
        #[Assert\NotNull(message: 'error.email.required')]
        #[Assert\NotBlank(message: 'error.email.notBlank')]
        #[Assert\Email(message: 'error.email.invalid')]
        public string $email,
    ) {}
}
