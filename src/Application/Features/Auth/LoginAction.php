<?php

namespace App\Application\Features\Auth;

use App\Application\Exceptions\UnauthorizedException;
use App\Application\Interfaces\InvokerInterface;
use App\Domain\Entities\Users\AccessToken;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class LoginAction implements InvokerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private string $accessTokenExpiry,
    )
    {
    }

    public function __invoke(string $email, #[SensitiveParameter] string $password): AccessToken
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null) {
            throw new UnauthorizedException();
        }

        if ($user->validatePassword($password, $this->passwordHasher->isPasswordValid(...)) === false) {
            throw new UnauthorizedException();
        }

        $accessToken = new AccessToken($user, $this->accessTokenExpiry);

        $this->entityManager->persist($accessToken);
        $this->entityManager->flush();

        return $accessToken;
    }
}
