<?php

namespace App\Application\Exceptions;

use App\Domain\Entities\EntityInterface;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

/** @SuppressUnused */
class EntityNotFoundException extends RuntimeException implements ApplicationExceptionInterface
{
    /** @param class-string<EntityInterface> $class */
    public function __construct(string $class, int $code = 404, ?Throwable $previous = null)
    {
        if (in_array(EntityInterface::class, class_implements($class) ?? [], true) === false) {
            throw new InvalidArgumentException("$class must implement App\Domain\EntityInterface");
        }

        $class = substr($class, strrpos($class, '\\') + 1);
        $class = strtolower($class);
        parent::__construct("error.$class.notFound", $code, $previous);
    }
}
