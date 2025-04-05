<?php

declare(strict_types=1);

namespace App\Application\Features\Admin\Users;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

readonly class CreateUserInvoker implements InvokerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(string $email, #[SensitiveParameter] string $password, array $roles): User
    {
        $user = new User($email, $password, $roles, $this->userPasswordHasher);
        $userExists = (bool) $this->entityManager->getRepository(User::class)->count(['email' => $email]);

        if ($userExists) {
            throw new DuplicateEntityException(User::class);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
