# phpstan-illuminate-collections

PHPStan type narrowing for `filter()`, `reject()`, `where()` and `values()->all()` on [`illuminate/collections`](https://github.com/illuminate/collections), for projects that use collections without Laravel (e.g. WordPress).

The filter/reject/where part is ported from [Larastan](https://github.com/larastan/larastan) (`CollectionFilterRejectDynamicReturnTypeExtension`), which can't be installed without large parts of the Laravel framework. If you use Laravel, use Larastan instead.

```php
/** @var Collection<int, ?Post> $posts */
$posts->filter();                          // Collection<int, Post>
$posts->filter(fn ($p) => $p !== null);    // Collection<int, Post>
$posts->reject();                          // Collection<int, null>
$posts->filter()->values()->all();         // list<Post> (instead of array<int, Post>)
```

`values()->all()` only returns a `list` when chained directly, not via a variable holding the `values()` result.

## Installation

```sh
composer require --dev hirasso/phpstan-illuminate-collections
```

With [phpstan/extension-installer](https://github.com/phpstan/extension-installer) you're done. Otherwise, add this to your `phpstan.neon`:

```neon
includes:
    - vendor/hirasso/phpstan-illuminate-collections/extension.neon
```

## Development

```sh
composer test
composer analyse
```
