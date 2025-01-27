<?php

namespace App\Application\Features\Auth;

use App\Domain\Users\AccessToken;
use App\Domain\Users\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class DeleteAccessTokensAction
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(User $user): void
    {
        $queryBuilder = $this->entityManager->getRepository(AccessToken::class)->createQueryBuilder('at');
        $queryBuilder->delete(AccessToken::class, 'at')->where('at.user = :user')->setParameter('user', $user);
        $queryBuilder->getQuery()->execute();
    }
}
