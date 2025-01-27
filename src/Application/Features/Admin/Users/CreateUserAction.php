<?php

namespace App\Application\Features\Admin\Users;

use App\Application\Exceptions\DuplicateEntityException;
use App\Domain\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

readonly class CreateUserAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(string $email, #[SensitiveParameter] string $password, array $roles): User
    {
        $user = new User($email, $password, $roles, $this->userPasswordHasher->hashPassword(...));

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            DuplicateEntityException::throwFrom($e, User::class);
        }

        return $user;
    }
}
