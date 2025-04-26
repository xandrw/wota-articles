<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users\UpdateUser;

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

        #[SensitiveParameter]
        #[Assert\When(
            expression: 'this.password !== null',
            constraints: [
                new Assert\NotBlank(message: 'error.password.notBlank'),
                new Assert\Length(
                    min: 8,
                    max: 255,
                    minMessage: 'error.password.minLength',
                    maxMessage: 'error.password.maxLength',
                ),
            ]
        )]
        public ?string $password = null,

        #[Assert\When(
            expression: 'this.roles !== null',
            constraints: [
                new Assert\Count(min: 1, minMessage: 'error.roles.minCount'),
                new Assert\All([
                    new Assert\Choice(choices: User::ROLES, message: 'error.roles.invalid'),
                ]),
            ],
        )]
        public ?array $roles = null,
    ) {}
}
