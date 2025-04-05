<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users;

use App\Domain\Entities\Users\User;

readonly class UserResponse
{
    public function __construct(public int $id, public string $email, public array $roles) {}

    public static function fromEntity(User $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->getRoles());
    }
}
