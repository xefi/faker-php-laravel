---
name: faker-php-laravel
description: Explains what the xefi/faker-php-laravel package does (Laravel integration for Faker PHP) and when to use it for generating fake data in a Laravel application (seeders, factories, tests, fixtures).
---

# xefi/faker-php-laravel

## What the underlying `xefi/faker-php` package does

`xefi/faker-php` is the core library that actually generates the fake data. It is a modern, PHP 8.3+ fake data generator built around a small `Faker` facade backed by a `Container`. You instantiate it once and call methods on it to produce realistic-looking values for tests, seeders, fixtures or prototyping:

```php
$faker = new \Xefi\Faker\Faker();

$faker->name();     // "John Doe"
$faker->sentence(); // "equus canis populus servus aquaeductus fidelitas"
$faker->iban();     // "PX41711762752955497163783543"
```

The library is organized around a few extensible concepts:

- **Extensions** — the actual generators, grouped by domain: `Person`, `Internet`, `Financial`, `Geographical`, `DateTime`, `Phone`, `Numbers`, `Strings`, `Text`, `Colors`, `Hash`, `Boolean`, `Array`. Each extension exposes methods like `name()`, `email()`, `iban()`, `ipv4()`, etc.
- **Modifiers** — value transformers chained on a call: `uppercase`, `lowercase`, `ucfirst`, `nullable`. Example: `$faker->nullable()->name()` may return `null` instead of a name.
- **Strategies** — control over how a value is produced: `unique` (avoid duplicates), `valid` (constrained by a callback), `regex` (match a pattern). Example: `$faker->unique()->email()`.
- **Locales** — generators are locale-aware (BCP 47 code passed to the `Faker` constructor); the built-in `default` locale is used when none is provided.
- **Packages / Manifests** — third-party packages can register their own extensions, modifiers and strategies, discovered through a manifest cache. This is what makes the ecosystem extensible.
- **Seeds** — the random source can be seeded for reproducible output in tests.

The library is **not Laravel-specific** — it works in any PHP 8.3+ project. The `xefi/faker-php-laravel` package below only wires it into Laravel.

## What `xefi/faker-php-laravel` adds on top

`xefi/faker-php-laravel` is the integration bridge between the [`xefi/faker-php`](https://faker-php.xefi.com) library and the Laravel framework. It does not generate fake data itself — it simply registers Faker PHP as a Laravel service and exposes a global helper to use it conveniently inside a Laravel application.

Concretely, the package provides three things:

1. **An auto-discovered Service Provider** (`Xefi\Faker\Laravel\FakerLaravelServiceProvider`, declared in `composer.json` via `extra.laravel.providers`):
   - registers `Xefi\Faker\Faker` as a **singleton** in the container, instantiated with the locale read from `config('app.faker_locale')`;
   - sets, at boot, the path of the Faker packages manifest (`bootstrap/cache/faker-packages.php`) and the application `basePath`, so the Faker PHP packages/extensions system works within the Laravel context.

2. **A global `faker()` helper** (`src/helpers.php`, loaded via `autoload.files`):
   - `faker()` → returns a Faker instance in the app's default locale (`app.faker_locale`, fallback `en_US`);
   - `faker('fr_FR')` → returns a Faker instance in the requested locale, cached as a singleton per locale in the container (key `Xefi\Faker\Faker:<locale>`);
   - `faker(null)` → Faker instance with locale `default` (no localization).

3. **Support for modern Laravel versions**: `illuminate/support` `^11.0|^12.0|^13.0`, PHP `>=8.3 <8.6`. If the project targets an older Laravel version, this package is not compatible.

Once installed, typical usage is simply:

```php
faker()->name();           // in the app's locale
faker()->sentence();
faker('fr_FR')->address(); // force a one-off locale
```

For the list of available methods (`name`, `sentence`, `iban`, etc.), refer to the [`xefi/faker-php`](https://faker-php.xefi.com) documentation — this package only exposes it.

## When to use it

Use this package when:

- You work on a **Laravel 11, 12 or 13 application** (PHP 8.3+) that needs to generate fake data.
- You want to replace `fakerphp/faker` (the legacy integration shipped with Laravel) with the Xefi Faker PHP ecosystem, e.g. to take advantage of its extensions/packages.
- You write **seeders**, **Eloquent factories**, **test fixtures** or you prototype a UI with placeholder content, and you want a global `faker()` helper rather than instantiating `new \Xefi\Faker\Faker()` everywhere.
- You need **clean locale handling**: one singleton instance per locale, aligned with `config('app.faker_locale')`.

## When NOT to use it

- **Non-Laravel project** (Symfony, Slim, plain PHP script…): use `xefi/faker-php` directly — this package brings nothing outside the Laravel container.
- **Laravel ≤ 10 or PHP < 8.3**: unsupported versions, install will fail.
- **You only need Faker at runtime in production** (not for seeding/testing): generate real data instead; Faker is a dev/test tool.
- **You are already committed to `fakerphp/faker`** and have no reason to migrate: both can coexist, but the `fake()` helper (Laravel native) and `faker()` (this package) are distinct — avoid confusion.

## Things worth knowing

- The helper is called `faker()`, not `fake()` — `fake()` stays as the one from Laravel/`fakerphp/faker` if you have it installed in parallel.
- The default locale comes from `config/app.php` → `faker_locale` key. To override it ad-hoc without touching the config: `faker('fr_FR')`.
- The singleton is cached **per locale**: calling `faker('fr_FR')` twice returns the same instance; `faker('en_US')` returns a different one.
- The service provider is auto-discovered via `extra.laravel.providers` — no need to add it manually to `config/app.php`.
- The Faker packages manifest is written to `bootstrap/cache/faker-packages.php`; if it gets corrupted, delete it and re-run.

## Further reading

- Faker PHP docs: https://faker-php.xefi.com
- Parent repository: `xefi/faker-php` (the actual generation library)
- This package: `xefi/faker-php-laravel` (Laravel glue code only)
