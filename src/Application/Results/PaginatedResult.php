<?php

namespace App\Application\Results;

use App\Domain\Entities\EntityInterface;

/**
 * @template T of EntityInterface
 */
readonly class PaginatedResult
{
    public function __construct(
        /** @var T[] */
        public array $items,
        public int $total,
        public int $pageNumber,
        public int $pageSize,
    )
    {
    }
}
