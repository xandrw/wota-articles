<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users\CreateUser;

use App\Domain\Entities\Users\User;
use SensitiveParameter;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUserRequest
{
    public function __construct(
        #[Assert\NotNull(message: 'error.email.required')]
        #[Assert\NotBlank(message: 'error.email.notBlank')]
        #[Assert\Email(message: 'error.email.invalid')]
        public string $email,

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

        #[Assert\NotNull(message: 'error.roles.required')]
        #[Assert\NotBlank(message: 'error.roles.notBlank')]
        #[Assert\All([
            new Assert\Choice(
                choices: User::ROLES,
                message: 'error.roles.invalid',
            ),
        ])]
        #[Assert\Count(min: 1, minMessage: 'error.roles.minCount')]
        public array $roles = [],
    )
    {
    }
}
