<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use App\Domain\Entities\EntityInterface;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class DuplicateEntityException extends RuntimeException implements ApplicationExceptionInterface
{
    /** @param class-string<EntityInterface> $class */
    public function __construct(string $class, int $code = 409, ?Throwable $previous = null)
    {
        if (is_subclass_of($class, EntityInterface::class) === false) {
            throw new InvalidArgumentException("$class must implement " . EntityInterface::class);
        }

        $class = substr($class, strrpos($class, '\\') + 1);
        $class = strtolower($class);

        parent::__construct("error.$class.exists", $code, $previous);
    }
}
