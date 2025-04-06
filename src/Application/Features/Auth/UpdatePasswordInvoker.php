<?php

declare(strict_types=1);

namespace App\Application\Features\Auth;

use App\Application\Exceptions\UnauthorizedException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\Events\PasswordChangedEvent;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UpdatePasswordInvoker implements InvokerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $eventDispatcher,
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
        $this->eventDispatcher->dispatch($this->getEvent($user), PasswordChangedEvent::class);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    protected function getEvent(User $user): PasswordChangedEvent
    {
        return new PasswordChangedEvent($user);
    }
}
