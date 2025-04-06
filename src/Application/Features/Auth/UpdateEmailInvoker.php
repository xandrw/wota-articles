<?php

declare(strict_types=1);

namespace App\Application\Features\Auth;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class UpdateEmailInvoker implements InvokerInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(User $user, string $email): void
    {
        $userEmailExists = (bool) $this->entityManager->getRepository(User::class)->count(['email' => $email]);

        if ($userEmailExists) {
            throw new DuplicateEntityException(User::class);
        }

        $user->setEmail($email);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
