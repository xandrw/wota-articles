<?php

namespace App\Application\Features\Auth;

use App\Application\Exceptions\UnauthorizedException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class ChangePasswordInvoker implements InvokerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
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

        if ($user->validatePassword($oldPassword, $this->passwordHasher) === false) {
            throw new UnauthorizedException();
        }

        $user->setPassword($newPassword, $this->passwordHasher);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
