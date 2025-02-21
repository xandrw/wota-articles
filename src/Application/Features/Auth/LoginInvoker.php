<?php

namespace App\Application\Features\Auth;

use App\Application\Exceptions\UnauthorizedException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\AccessToken;
use App\Domain\Entities\Users\Events\UserLoggedInEvent;
use App\Domain\Entities\Users\User;
use App\Domain\Interfaces\RandomInterface;
use Doctrine\ORM\EntityManagerInterface;
use SensitiveParameter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class LoginInvoker implements InvokerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $eventDispatcher,
        private RandomInterface $randomizer,
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

        if ($user->validatePassword($password, $this->passwordHasher) === false) {
            throw new UnauthorizedException();
        }

        $accessToken = new AccessToken($user, $this->accessTokenExpiry, $this->randomizer);
        $this->eventDispatcher->dispatch($this->getEvent($user));
        $this->entityManager->persist($accessToken);
        $this->entityManager->flush();
        return $accessToken;
    }

    protected function getEvent(User $user): UserLoggedInEvent
    {
        return new UserLoggedInEvent($user);
    }
}
