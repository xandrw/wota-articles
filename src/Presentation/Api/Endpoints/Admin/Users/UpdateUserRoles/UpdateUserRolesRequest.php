<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users\UpdateUserRoles;

use App\Domain\Entities\Users\User;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateUserRolesRequest
{
    public function __construct(
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
    ) {}
}
