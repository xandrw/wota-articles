<?php

namespace App\Application\Features\Admin\Users;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Interfaces\InvokerInterface;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

readonly class CreateUserAction implements InvokerInterface
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
        $userExists = (bool) $this->entityManager->getRepository(User::class)->count(['email' => $email]);

        if ($userExists) {
            throw new DuplicateEntityException(User::class);
        }

        $user = new User($email, $password, $roles, $this->userPasswordHasher);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
