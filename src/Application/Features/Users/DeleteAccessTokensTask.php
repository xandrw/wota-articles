<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Application\Features\TaskInterface;
use App\Domain\Entities\Users\AccessToken;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
readonly class DeleteAccessTokensTask implements TaskInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(User $user): void
    {
        $queryBuilder = $this->entityManager->getRepository(AccessToken::class)->createQueryBuilder('at');
        $queryBuilder->delete(AccessToken::class, 'at')->where('at.user = :user')->setParameter('user', $user);
        $queryBuilder->getQuery()->execute();
    }
}
