<?php

declare(strict_types=1);

namespace App\Domain\Entities;

/**
 * Ignore: just a test class that emphasizes a more "data separate from procedures" approach
 */
abstract class PersonManager extends Person
{
    public static function create(string $firstName, string $lastName): Person
    {
        return new Person($firstName, $lastName);
    }

    public static function getFullName(Person $person): string
    {
        return "$person->firstName $person->lastName";
    }

    public static function update(Person $person, ?string $firstName = null, ?string $lastName = null): Person
    {
        $person->firstName = $firstName ?? $person->firstName;
        $person->lastName = $lastName ?? $person->lastName;

        return $person;
    }
}
