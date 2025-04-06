<?php

declare(strict_types=1);

namespace App\Application\Features\Admin\Users;

use App\Application\Exceptions\EntityNotFoundException;
use App\Application\Features\InvokerInterface;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class UpdateUserRoleInvoker implements InvokerInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(int $userId, array $roles): User
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if ($user === null) {
            throw new EntityNotFoundException(User::class);
        }

        $user->setRoles($roles);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
