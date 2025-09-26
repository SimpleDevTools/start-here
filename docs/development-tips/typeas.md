## TypeAs usage in this project (PHP static typing aid)

Status: `smpita/typeas` is already installed in this project.

Purpose: Use it to convert untyped/mixed values into strongly typed values safely, eliminating `mixed` at boundaries and improving Larastan/PHPStan analysis.

### Agent quick reference

- Use at the boundaries: request inputs, Livewire actions, DTO mappers, jobs/listeners, casts/accessors.
- Prefer `TypeAs::*` over ad-hoc casts for untrusted input. Use nullable variants if `null` is acceptable.
- Arrays: default wraps non-iterables; pass `false` to throw instead of wrapping when strictness is required.
- Class typing: `TypeAs::class(Target::class, $mixed[, $default])`.
- Helpers available (camelCased): `asString`, `asInt`, etc.

Decision rules:
- If PHPStan reports “mixed given/returned”, coerce immediately where the value enters the system.
- If comparing or doing math on a `mixed`, coerce to the exact type first (int/float/string).
- If indexing a `mixed` as array, coerce with `TypeAs::array($mixed, false)` to fail fast or provide a default `[]` if safe.

### Core usage

```php
<?php
declare(strict_types=1);

use Smpita\TypeAs\TypeAs;

// Throws TypeAsResolutionException on failure
$name = TypeAs::string($input['name']);

// Provide a safe default instead of throwing
$page = TypeAs::int($query['page'] ?? null, 1);

// Nullable variants (return null on failure)
$maybeEmail = TypeAs::nullableString($payload['email']);

// Class typing
$dto = TypeAs::class(MyDto::class, $mixedDto);

// Arrays: wrap non-iterables by default; pass false to disable wrapping
$tags = TypeAs::array($mixedTags);          // 'tag' => ['tag']
$strict = TypeAs::array($mixedTags, false); // throws on non-iterable
```

Helper functions also exist (camelCased):

```php
<?php
declare(strict_types=1);

use function Smpita\TypeAs\asString;

$title = asString($mixed, '');
```

### Where to use in this codebase

- Form Requests and Livewire actions: Normalize request payloads before validation-dependent logic.
- Data/DTO layers: Convert external API payloads or cast dynamic arrays into typed values.
- Model factories/seeders when coercing optional input.
- Casts and accessors: Use to harden values read from JSON columns or external stores.
- Jobs/listeners: Validate payload types on `handle()` entry.

### Common PHPStan/Larastan findings and fixes

- Parameter expects string, mixed given → `TypeAs::string($mixed, '')` at the call site or boundary.
- Cannot access offset on mixed → `TypeAs::array($mixed, false)` (strict) or provide a default `[]`.
- Property typed as mixed → Change property to concrete type and assign with `TypeAs::*` at set time.
- Binary operation/comparison on mixed → `TypeAs::int/float/string` before use.
- Method should return T but returns mixed → coerce before return.

### Laravel integration tips

- Always keep `declare(strict_types=1);` and explicit signatures; TypeAs complements, it doesn’t replace typing.
- Prefer `TypeAs::nullable*` when `null` is an acceptable business value; otherwise provide explicit defaults.
- For repeated patterns, create tiny private helpers or dedicated resolvers.

### Project patterns (copy/paste)

Form Request / Controller:

```php
<?php
declare(strict_types=1);

use Smpita\TypeAs\TypeAs;

$page = TypeAs::int($request->input('page'), 1);
$email = TypeAs::nullableString($request->input('email'));
$filters = TypeAs::array($request->input('filters'), []);
```

Livewire action:

```php
<?php
declare(strict_types=1);

use Smpita\TypeAs\TypeAs;

public function save(): void
{
    $quantity = TypeAs::int($this->form['quantity'] ?? null, 0);
    $notes = TypeAs::nullableString($this->form['notes'] ?? null);
    // ... proceed with validated, typed values
}
```

### Custom resolvers (optional)

```php
<?php
declare(strict_types=1);

use Smpita\TypeAs\Contracts\StringResolver;
use Smpita\TypeAs\TypeAs;

final class TrimmingStringResolver implements StringResolver
{
    public function resolve(mixed $value, string $default = null): string
    {
        if (! is_string($value)) {
            if ($default !== null) {
                return $default;
            }
            throw new \UnexpectedValueException('Expected string');
        }
        return trim($value);
    }
}

// Register globally (e.g., in a service provider boot method)
TypeAs::setStringResolver(new TrimmingStringResolver());
```

To restore defaults:

```php
TypeAs::useDefaultResolvers();
```

### Review checklist additions

- Replace ad-hoc `(string)`, `(int)`, `(array)` casts on external inputs with `TypeAs`.
- Avoid propagating `mixed` beyond entry points; coerce immediately.
- Prefer `nullable*` when exceptions are not desired; otherwise provide explicit defaults.

### Agent mapping

- code-reviewer: Flag ad-hoc casts and boundary `mixed`; recommend `TypeAs::*`/nullable.
- style-guardian: Enforce replacement of ad-hoc casts on untrusted inputs with `TypeAs`.
- laravel-architect: Coerce at entry points (requests, jobs, DTO mapping), keep signatures typed.
- debugger: When failures stem from unexpected shapes, add `TypeAs` coercion at the boundary.
- pest-writer/test-runner: Add tests for coercion edge cases (nulls, non-iterables, wrong types).


