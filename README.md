# phpstan-illuminate-collections

PHPStan type narrowing for `filter()`, `reject()` and `where()` on [`illuminate/collections`](https://github.com/illuminate/collections), for projects that use collections without Laravel (e.g. WordPress).

Ported from [Larastan](https://github.com/larastan/larastan) (`CollectionFilterRejectDynamicReturnTypeExtension`), which can't be installed without large parts of the Laravel framework. If you use Laravel, use Larastan instead.

```php
/** @var Collection<int, ?Post> $posts */
$posts->filter();                          // Collection<int, Post>
$posts->filter(fn ($p) => $p !== null);    // Collection<int, Post>
$posts->reject();                          // Collection<int, null>
```

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
