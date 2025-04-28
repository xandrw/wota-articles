<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Application\Exceptions\UnauthorizedException;
use App\Application\Features\TaskInterface;
use App\Domain\Entities\Users\AccessToken;
use App\Domain\Entities\Users\Events\UserLoggedInEvent;
use App\Domain\Entities\Users\User;
use App\Domain\Interfaces\RandomizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Autoconfigure(lazy: true)]
readonly class LoginTask implements TaskInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $eventDispatcher,
        private RandomizerInterface $randomizer,
        private int $accessTokenExpiry,
    ) {}

    /**
     * @throws UnauthorizedException
     */
    public function __invoke(string $email, #[SensitiveParameter] string $password): AccessToken
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null) {
            throw new UnauthorizedException();
        }

        if ($user->validatePassword($password, $this->passwordHasher) === false) {
            throw new UnauthorizedException();
        }

        $accessToken = new AccessToken($user, $this->accessTokenExpiry, $this->randomizer);
        $this->eventDispatcher->dispatch($this->getEvent($user), UserLoggedInEvent::class);
        $this->entityManager->persist($accessToken);
        $this->entityManager->flush();
        return $accessToken;
    }

    protected function getEvent(User $user): UserLoggedInEvent
    {
        return new UserLoggedInEvent($user);
    }
}
