<?php

namespace App\WebApi\Endpoints\Admin\Users;

use App\Domain\Users\User;

readonly class UserResponse
{
    public function __construct(public int $id, public string $email, public array $roles)
    {
    }

    public static function fromEntity(User $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->getRoles());
    }
}
