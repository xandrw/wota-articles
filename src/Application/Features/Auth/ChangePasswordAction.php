<?php

namespace App\Application\Features\Auth;

use App\Application\Exceptions\UnauthorizedException;
use App\Application\Interfaces\InvokerInterface;
use App\Domain\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class ChangePasswordAction implements InvokerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private DeleteAccessTokensAction $deleteTokensAction,
    )
    {
    }

    public function __invoke(
        string $email,
        #[SensitiveParameter] string $oldPassword,
        #[SensitiveParameter] string $newPassword,
    ): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null) {
            throw new UnauthorizedException();
        }

        if ($user->validatePassword($oldPassword, $this->passwordHasher->isPasswordValid(...)) === false) {
            throw new UnauthorizedException();
        }

        $this->deleteTokensAction->__invoke($user);

        $user->setPassword($newPassword, $this->passwordHasher->hashPassword(...));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
