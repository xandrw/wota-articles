<?php

declare(strict_types=1);

namespace App\Application\Results;

use App\Domain\Entities\EntityInterface;
use Traversable;

/**
 * @template T of EntityInterface
 */
readonly class PaginatedResult
{
    public function __construct(
        /** @var T[]|Traversable<int, T> */
        public iterable $items,
        public int $total,
        public int $pageNumber,
        public int $pageSize,
    ) {}
}
