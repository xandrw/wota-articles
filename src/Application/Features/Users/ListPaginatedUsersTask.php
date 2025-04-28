<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Application\Features\TaskInterface;
use App\Application\Results\PaginatedResult;
use App\Domain\Entities\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
readonly class ListPaginatedUsersTask implements TaskInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    /**
     * @return PaginatedResult<User>
     * @throws Exception
     */
    public function __invoke(int $pageNumber, int $pageSize): PaginatedResult
    {
        $pageNumber = max(1, $pageNumber);
        $pageSize = max(1, $pageSize);

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
