<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Application\Exceptions\EntityNotFoundException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
readonly class RemoveUserInvoker implements InvokerInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(int $userId): void
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if ($user === null) {
            throw new EntityNotFoundException(User::class);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
