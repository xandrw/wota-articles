<?php

declare(strict_types=1);

namespace App\Domain\Entities;

/**
 * Ignore: just a test class that emphasizes a more "data separate from procedures" approach
 */
abstract class FooManager extends Foo
{
    public static function create(string $name): Foo
    {
        return new Foo($name);
    }

    public static function getName(Foo $foo): string
    {
        return $foo->name;
    }

    public static function setName(Foo $foo, string $name): Foo
    {
        $foo->name = $name;
        return $foo;
    }
}
