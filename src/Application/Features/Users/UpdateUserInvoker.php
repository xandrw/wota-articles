<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Exceptions\UnauthorizedException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\Events\UserCredentialsChanged;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UpdateUserInvoker implements InvokerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function __invoke(
        User $user,
        ?string $email = null,
        #[SensitiveParameter]
        ?string $oldPassword = null,
        #[SensitiveParameter]
        ?string $newPassword = null,
        ?array $roles = null,
    ): User
    {
        $this->trySetEmail($user, $email);
        $this->trySetPassword($user, $oldPassword, $newPassword);

        if ($roles !== null) {
            $user->setRoles($roles);
        }

        $this->eventDispatcher->dispatch($this->getEvent($user), UserCredentialsChanged::class);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    protected function trySetEmail(User $user, ?string $email): void
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

    protected function trySetPassword(
        User $user,
        #[SensitiveParameter]
        ?string $oldPassword,
        #[SensitiveParameter]
        ?string $newPassword,
    ): void
    {
        if (empty($oldPassword) || empty($newPassword) || $oldPassword === $newPassword) {
            return;
        }

        if ($user->validatePassword($oldPassword, $this->passwordHasher) === false) {
            throw new UnauthorizedException();
        }

        $user->setPassword($newPassword, $this->passwordHasher);
    }

    protected function getEvent(User $user): UserCredentialsChanged
    {
        return new UserCredentialsChanged($user);
    }
}
