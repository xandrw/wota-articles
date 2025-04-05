<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Auth\ChangePassword;

use SensitiveParameter;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ChangePasswordRequest
{
    public function __construct(
        #[Assert\NotNull(message: 'error.oldPassword.required')]
        #[Assert\NotBlank(message: 'error.oldPassword.notBlank')]
        #[Assert\Length(
            min: 8,
            max: 255,
            minMessage: 'error.oldPassword.minLength',
            maxMessage: 'error.oldPassword.maxLength',
        )]
        #[SensitiveParameter]
        public string $oldPassword,

        #[Assert\NotNull(message: 'error.password.required')]
        #[Assert\NotBlank(message: 'error.password.notBlank')]
        #[Assert\Length(
            min: 8,
            max: 255,
            minMessage: 'error.password.minLength',
            maxMessage: 'error.password.maxLength',
        )]
        #[SensitiveParameter]
        public string $password,
    ) {}
}
