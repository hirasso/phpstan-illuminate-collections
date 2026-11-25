<?php

namespace CollectionValuesAll;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

use function PHPStan\Testing\assertType;

class User {}

/**
 * @param Collection<string, User> $users
 * @param Collection<int, ?User> $nullableUsers
 * @param LazyCollection<string, User> $lazyUsers
 */
function test(Collection $users, Collection $nullableUsers, LazyCollection $lazyUsers): void
{
    assertType('list<CollectionValuesAll\User>', $users->values()->all());

    assertType('list<CollectionValuesAll\User>', $nullableUsers->filter()->values()->all());

    assertType('list<CollectionValuesAll\User>', $lazyUsers->values()->all());

    assertType('array<string, CollectionValuesAll\User>', $users->all());

    $values = $users->values();
    assertType('array<int, CollectionValuesAll\User>', $values->all());
}
