<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Users\Login;

use App\Domain\Entities\Users\AccessToken;

readonly class LoginResponse
{
    public function __construct(public int $id, public string $email, public array $roles, public string $token) {}

    public static function fromEntity(AccessToken $accessToken): self
    {
        $user = $accessToken->getUser();
        return new self($user->getId(), $user->getEmail(), $user->getRoles(), $accessToken->getToken());
    }
}
