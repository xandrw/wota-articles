<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Users;

use SensitiveParameter;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateLoggedInUserRequest
{
    public function __construct(
        #[Assert\When(
            expression: 'this.email !== null',
            constraints: [
                new Assert\NotBlank(message: 'error.email.notBlank', allowNull: false),
                new Assert\Email(message: 'error.email.invalid'),
            ],
        )]
        public ?string $email = null,

        #[SensitiveParameter]
        #[Assert\When(
            expression: 'this.email !== null && this.oldPassword !== null && this.newPassword !== null',
            constraints: [
                new Assert\NotBlank(message: 'error.oldPassword.notBlank'),
                new Assert\Length(
                    min: 8,
                    max: 255,
                    minMessage: 'error.oldPassword.minLength',
                    maxMessage: 'error.oldPassword.maxLength',
                ),
            ]
        )]
        public ?string $oldPassword = null,

        #[SensitiveParameter]
        #[Assert\When(
            expression: 'this.email !== null && this.newPassword !== null && this.oldPassword !== null',
            constraints: [
                new Assert\NotBlank(message: 'error.newPassword.notBlank'),
                new Assert\Length(
                    min: 8,
                    max: 255,
                    minMessage: 'error.newPassword.minLength',
                    maxMessage: 'error.newPassword.maxLength',
                ),
            ]
        )]
        public ?string $newPassword = null,
    ) {}
}
