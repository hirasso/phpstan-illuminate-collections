<?php

namespace CollectionReject;

use Illuminate\Support\Collection;

use function PHPStan\Testing\assertType;

class User
{
    public bool $blocked = false;
}

class Account {}

function convertToAccount(User $user): ?Account
{
    return $user->blocked ? null : new Account();
}

/** @param Collection<int, User> $users */
function test(Collection $users): void
{
    assertType("Illuminate\Support\Collection<int, ''|'0'|null>", collect(['foo', null, '', 'bar', null])->reject());

    assertType('Illuminate\Support\Collection<int, int<min, 2>>', collect([1, 2, 3, 4, 5, 6])->reject(function (int $value) {
        return $value > 2;
    }));
    assertType('Illuminate\Support\Collection<int, int<min, 2>>', collect([1, 2, 3, 4, 5, 6])->reject(fn (int $value) => $value > 2));

    assertType('Illuminate\Support\Collection<int, CollectionReject\User>', $users->reject(fn (User $user) => ! $user->blocked));

    assertType(
        'Illuminate\Support\Collection<int, null>',
        $users->map(fn (User $user): ?Account => convertToAccount($user))->reject()
    );
}
