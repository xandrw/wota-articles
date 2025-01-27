<?php

namespace App\Application\Exceptions;

use App\Domain\EntityInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class DuplicateEntityException extends RuntimeException implements ApplicationExceptionInterface
{
    /** @param class-string<EntityInterface> $class */
    public function __construct(string $class, int $code = 409, ?Throwable $previous = null)
    {
        if (in_array(EntityInterface::class, class_implements($class) ?? [], true) === false) {
            throw new InvalidArgumentException("$class must implement {${EntityInterface::class}}");
        }

        $class = substr($class, strrpos($class, '\\') + 1);

        parent::__construct("$class already exists", $code, $previous);
    }

    /**
     * @throws Throwable
     */
    public static function throwFrom(Throwable $throwable, string $class): Throwable
    {
        if (is_a($throwable, UniqueConstraintViolationException::class, true)) {
            throw new self(class: $class, previous: $throwable);
        }

        $previous = $throwable->getPrevious();

        if (is_a($previous, UniqueConstraintViolationException::class, true)) {
            throw new self(class: $class, previous: $previous);
        }

        throw $throwable;
    }
}
