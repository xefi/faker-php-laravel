---
name: faker-php-laravel-development
description: Build and work with xefi/faker-php-laravel features, including the global faker() helper, locale-aware singletons, and the Faker PHP extensions/modifiers/strategies system inside a Laravel application.
---

# Faker PHP Laravel Development

## When to use this skill

Use this skill when working with the `xefi/faker-php-laravel` package in a Laravel 11/12/13 application (PHP 8.3+) to generate fake data — typically in seeders, Eloquent factories, test fixtures, or UI prototypes. It is the Laravel integration of the `xefi/faker-php` library: it does not generate data itself, it exposes Faker PHP as a container singleton and a global `faker()` helper.

If `xefi/faker-php-laravel` is installed in the project, **always use `faker()` and never the legacy `fake()` / `fakerphp/faker`** — mixing both fragments fake-data generation across the codebase and defeats the point of installing this package.

Do not use this skill for: non-Laravel projects (use `xefi/faker-php` directly), Laravel ≤ 10, PHP < 8.3, or runtime production data generation (Faker is a dev/test tool).

## Features

- **Global `faker()` helper**: returns a Faker instance using the app's default locale (`config('app.faker_locale')`, fallback `en_US`). Loaded via `autoload.files` in `src/helpers.php`. Example usage:

```php
faker()->name();      // "John Doe"
faker()->sentence();  // "equus canis populus servus aquaeductus"
faker()->iban();      // "PX41711762752955497163783543"
```

- **Per-locale singleton**: passing a locale to `faker()` returns a Faker instance cached as a singleton under the key `Xefi\Faker\Faker:<locale>`. Two calls with the same locale return the same instance. Example usage:

```php
faker('fr_FR')->address();  // force a one-off locale
faker()->name();        // no localization (default locale)
```

- **Auto-discovered Service Provider** (`Xefi\Faker\Laravel\FakerLaravelServiceProvider`): registered via `extra.laravel.providers` in `composer.json`, no manual registration needed. Binds `Xefi\Faker\Faker` as a singleton built from `config('app.faker_locale')`, and wires the Faker packages manifest path and application `basePath` at boot.

- **Extensions** (generators grouped by domain): `Person`, `Internet`, `Financial`, `Geographical`, `DateTime`, `Phone`, `Numbers`, `Strings`, `Text`, `Colors`, `Hash`, `Boolean`, `Array`. Each exposes methods like `name()`, `email()`, `iban()`, `ipv4()`. Example usage:

```php
faker()->email();
faker()->ipv4();
```

- **Modifiers** (chained value transformers): `uppercase`, `lowercase`, `ucfirst`, `nullable`. Example usage:

```php
faker()->nullable()->name();   // may return null
faker()->uppercase()->name();
```

- **Strategies** (control how a value is produced): `unique` (no duplicates), `valid` (constrained by callback), `regex` (match a pattern). Example usage:

```php
faker()->unique()->email();
faker()->regex('/^[A-Z]{3}-\d{4}$/')->string();
```

- **Seeded random source** for reproducible output in tests. Example usage:

```php
faker()->seed(1234);
faker()->name();  // deterministic
```

- **Packages / Manifests**: third-party packages can register their own extensions, modifiers and strategies through a manifest cache. The full list of methods available on `faker()` in this project (including third-party packages) is discoverable from `bootstrap/cache/faker-packages.php` — read it directly when you need to know what `faker()` can do, rather than guessing from the docs. If the manifest gets corrupted, delete it and re-run.

## Further reading

- Faker PHP docs: https://faker-php.xefi.com
- Parent library: `xefi/faker-php` (generation engine, framework-agnostic)
- This package: `xefi/faker-php-laravel` (Laravel glue code only)
