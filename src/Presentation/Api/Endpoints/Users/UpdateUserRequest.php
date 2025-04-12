<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Users;

use App\Domain\Entities\Users\User;
use SensitiveParameter;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateUserRequest
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
        #[SensitiveParameter]
        public ?string $oldPassword = null,

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
        #[SensitiveParameter]
        public ?string $newPassword = null,

        // Comment reason: Will use in another request
        // #[Assert\When(
        //     expression: 'this.roles !== null',
        //     constraints: [
        //         new Assert\Count(min: 1, minMessage: 'error.roles.minCount'),
        //         new Assert\All([
        //             new Assert\Choice(choices: User::ROLES, message: 'error.roles.invalid'),
        //         ]),
        //     ],
        // )]
        // public ?array $roles = null,
    ) {}
}
