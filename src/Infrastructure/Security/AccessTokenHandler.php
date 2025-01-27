<?php

namespace App\Infrastructure\Security;

use App\Domain\Users\AccessToken;
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
            throw new AuthenticationException();
        }

        return new UserBadge($token->getUser()->getEmail(), fn() => $token->getUser());
    }
}
