<?php

namespace App\Application\Features\Auth;

use App\Application\Exceptions\InvalidCredentialsException;
use App\Domain\Users\AccessToken;
use App\Domain\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class LoginAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private DeleteAccessTokensAction $deleteTokensAction,
        private string $accessTokenExpiry,
    )
    {
    }

    public function __invoke(string $email, string $password): AccessToken
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null || $this->passwordHasher->isPasswordValid($user, $password) === false) {
            throw new InvalidCredentialsException();
        }

        // TODO: Could reimplement this as a event, in case of a password change endpoint
        $this->deleteTokensAction->__invoke($user);

        $accessToken = new AccessToken($user, $this->accessTokenExpiry);

        $this->entityManager->persist($accessToken);
        $this->entityManager->flush();

        return $accessToken;
    }
}
