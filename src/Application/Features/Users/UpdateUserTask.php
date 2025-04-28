<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Features\TaskInterface;
use App\Domain\Entities\Users\Events\UserCredentialsChanged;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Autoconfigure(lazy: true)]
readonly class UpdateUserTask implements TaskInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @throws DuplicateEntityException
     */
    public function __invoke(
        User $user,
        string $email,
        #[SensitiveParameter]
        string $password,
        ?array $roles = null,
    ): User
    {
        $this->trySetEmail($user, $email);
        $user->setPassword($password, $this->passwordHasher);

        if ($roles !== null) {
            $user->setRoles($roles);
        }

        $this->eventDispatcher->dispatch($this->getEvent($user), UserCredentialsChanged::class);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @throws DuplicateEntityException
     */
    protected function trySetEmail(User $user, string $email): void
    {
        if (empty($email) || $email === $user->getEmail()) {
            return;
        }

        $userEmailExists = (bool) $this->entityManager->getRepository(User::class)->count(['email' => $email]);

        if ($userEmailExists) {
            throw new DuplicateEntityException(User::class);
        }

        $user->setEmail($email);
    }

    protected function getEvent(User $user): UserCredentialsChanged
    {
        return new UserCredentialsChanged($user);
    }
}
