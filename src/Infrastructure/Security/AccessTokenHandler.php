<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Entities\Users\AccessToken;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

/** @SuppressUnused */
readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getUserBadgeFrom(#[SensitiveParameter] string $accessToken): UserBadge
    {
        $token = $this->entityManager->getRepository(AccessToken::class)->findOneBy(['token' => $accessToken]);

        if ($token === null || $token->isExpired()) {
            // This is caught in AuthenticatorManager
            // An empty 401 response is returned
            throw new AuthenticationException();
        }

        return new UserBadge($token->getUser()->getEmail(), fn() => $token->getUser());
    }
}
