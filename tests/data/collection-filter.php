<?php

namespace CollectionFilter;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

use function PHPStan\Testing\assertType;

class User
{
    public bool $blocked = false;
}

class Account {}

function convertToAccount(User $user): ?Account {}

/**
 * @param Collection<int, User> $users
 * @param Collection<array-key, mixed> $mixedCollection
 * @param LazyCollection<int, ?User> $lazyUsers
 */
function test(Collection $users, Collection $mixedCollection, LazyCollection $lazyUsers): void
{
    assertType('Illuminate\Support\Collection<int, non-falsy-string>', collect(['foo', null, '', 'bar', null])->filter());

    assertType('Illuminate\Support\Collection<int, int<3, max>>', collect([1, 2, 3, 4, 5, 6])->filter(function (int $value) {
        return $value > 2;
    }));
    assertType('Illuminate\Support\Collection<int, int<3, max>>', collect([1, 2, 3, 4, 5, 6])->filter(fn (int $value) => $value > 2));

    assertType('Illuminate\Support\Collection<int, CollectionFilter\User>', $users->filter(fn (User $user) => ! $user->blocked));

    assertType(
        'Illuminate\Support\Collection<int, CollectionFilter\Account>',
        $users->map(fn (User $user): ?Account => convertToAccount($user))->filter()
    );

    assertType(
        'Illuminate\Support\Collection<int, CollectionFilter\Account>',
        $users->map(fn (User $user): ?Account => convertToAccount($user))->filter(fn (?Account $a) => $a !== null)
    );

    assertType('Illuminate\Support\LazyCollection<int, CollectionFilter\User>', $lazyUsers->filter());

    assertType("Illuminate\Support\Collection<(int|string), mixed~(0|0.0|''|'0'|array{}|false|null)>", $mixedCollection->pluck('foo')->filter());
}
