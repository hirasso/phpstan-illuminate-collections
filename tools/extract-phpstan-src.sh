#!/usr/bin/env bash
# Intelephense can't read phars: extract PHPStan's and PhpParser's sources for IDE indexing
set -e

phar=vendor/phpstan/phpstan/phpstan.phar
dest=vendor/phpstan/phpstan/extracted

[ -f "$phar" ] || exit 0

rm -rf "$dest"
php -r '(new Phar($argv[1]))->extractTo($argv[2], ["src/", "vendor/nikic/php-parser/lib/"]);' "$phar" "$dest"
