## ConfigAs usage in this project (typed Laravel config)

Status: `smpita/configas` is already installed in this project.

Purpose: Use `smpita/configas` to return strongly typed values from Laravel `config()` keys, eliminating `mixed` from configuration access and improving Larastan/PHPStan analysis.

### Agent quick reference

- Use for any `config()` access in providers, controllers, middleware, jobs, and Livewire components.
- Prefer `ConfigAs::*` over ad-hoc casts on `config()` (e.g., avoid `(int) config('key')`).
- Nullable variants exist when `null` is acceptable; otherwise provide explicit defaults.
- Values are cached per-request; use `fresh*` methods for uncached reads and `forget/flush` to invalidate.

Decision rules:
- If PHPStan reports mixed from `config()`, replace with the corresponding `ConfigAs::type('key', default)`.
- If reading arrays/objects, choose strictness:
  - `ConfigAs::array('key')` returns array (throws if not coercible)
  - `ConfigAs::nullableArray('key')` returns `null` on failure
- When expecting a class instance, use `ConfigAs::class(Target::class, 'key', $default)`.

### Core usage

```php
<?php
declare(strict_types=1);

use Smpita\ConfigAs\ConfigAs;

// Simple types
$timeout = ConfigAs::int('api.timeout', 30);
$feature = ConfigAs::bool('features.example', false);
$email = ConfigAs::nullableString('app.admin_email');

// Arrays
$hosts = ConfigAs::array('services.search.hosts', []);
$maybeTags = ConfigAs::nullableArray('app.tags');

// Class typing
$driver = ConfigAs::class(MyDriver::class, 'services.payment.driver', new DefaultDriver());

// Fresh read (bypass per-request cache)
$freshTimeout = ConfigAs::freshInt('api.timeout', 30);
$freshEmail = ConfigAs::freshString('app.admin_email');
```

Caching controls:

```php
<?php
declare(strict_types=1);

use Smpita\ConfigAs\ConfigAs;

// Forget a single key/type
ConfigAs::forgetInt('api.timeout');

// Flush all cached ints
ConfigAs::flushInts();

// Flush everything
ConfigAs::flush();
```

Helpers:

```php
<?php
declare(strict_types=1);

use function Smpita\ConfigAs\configAs;
use function Smpita\ConfigAs\configString;

$cfg = configAs();
$name = $cfg->string('app.name', 'GO');
$dsn = configString('database.connections.pgsql.dsn', '');
```

### Where to use in this codebase

- Service providers and middleware: read typed toggles and thresholds.
- Controllers and Livewire components: typed limits, feature flags, labels.
- Jobs/listeners: typed retry/backoff values from config.

### Common PHPStan/Larastan findings and fixes

- `mixed` from `config()` � replace with `ConfigAs::type('key', default)` or `ConfigAs::nullableType('key')`.
- Invalid offset on `config()` result � use `ConfigAs::array('key', [])` before indexing.
- Expected class, mixed given � `ConfigAs::class(Target::class, 'key', $default)`.

### Project patterns (copy/paste)

```php
<?php
declare(strict_types=1);

use Smpita\ConfigAs\ConfigAs;

// Provider
$maxJobs = ConfigAs::int('queue.max_jobs', 100);

// Controller / Livewire
$perPage = ConfigAs::int('ui.per_page', 15);
$features = ConfigAs::array('app.features', []);
```

### Notes

- ConfigAs builds on TypeAs; use TypeAs for external inputs and ConfigAs for configuration values.
- Favor explicit defaults for robust behavior; use `nullable*` only when `null` is a valid business value.

### Agent mapping

- code-reviewer: Flag direct `config()` usage with casts; recommend `ConfigAs::*`.
- style-guardian: Enforce typed config access over `config()` + casts.
- laravel-architect: Prefer ConfigAs in providers/middleware; keep signatures typed.
- debugger: For config-related issues, switch to ConfigAs and use `fresh*`/forget/flush when necessary.
- pest-writer/test-runner: Mock config values; test ConfigAs edge cases (missing keys, wrong types).