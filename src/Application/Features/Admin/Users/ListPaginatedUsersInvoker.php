<?php

namespace App\Application\Features\Admin\Users;

use App\Application\Features\InvokerInterface;
use App\Application\Results\PaginatedResult;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

readonly class ListPaginatedUsersInvoker implements InvokerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $f = \App\Infrastructure\Security\UuidRandomizer::class;
        $f2 = \App\Presentation\Api\Endpoints\Admin\Users\UserResponse::class;
    }

    /**
     * @return PaginatedResult<User>
     * @throws Exception
     */
    public function __invoke(int $pageNumber, int $pageSize): PaginatedResult
    {
        $queryBuilder = $this->entityManager
            ->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('u')
            ->setFirstResult(($pageNumber - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $paginator = new Paginator($queryBuilder);

        return new PaginatedResult(
            $paginator->getIterator(),
            $paginator->count(),
            $pageNumber,
            $pageSize,
        );
    }
}
