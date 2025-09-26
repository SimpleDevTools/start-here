# SOP: Creating a New Model

## Purpose
This SOP documents the complete process for creating Eloquent models in this Laravel v12 application, including migrations, factories, and comprehensive testing. It ensures consistency across all model implementations and follows established security and architectural patterns.

## Prerequisites
- Familiarity with Laravel Eloquent models
- Understanding of database migrations and factories
- Basic knowledge of Pest testing framework
- Access to the application codebase

## Step-by-Step Process

### Step 0: Study Existing Models for Consistency

**CRITICAL**: Before creating any new model, study existing models and their related files to understand established patterns and conventions.

#### Required Analysis:
1. **Examine existing models** - Review files in `app/Models/` to understand:
   - Trait usage patterns
   - Method signatures and type annotations
   - Relationship method implementations
   - Property configurations (`$guarded`, `$casts`, etc.)

2. **Review existing migrations** - Study files in `database/migrations/` to understand:
   - Column ordering conventions
   - Index placement patterns
   - Field naming conventions
   - Data type choices

3. **Analyze existing factories** - Check files in `database/factories/` to understand:
   - Factory state methods
   - Fake data patterns
   - Relationship handling
   - Method structure and naming

4. **Study existing tests** - Review files in `tests/Unit/` to understand:
   - Test structure and naming conventions
   - Assertion patterns
   - Coverage expectations
   - Testing approaches for relationships

#### Consistency Checklist:
- [ ] Model traits match existing patterns
- [ ] Method signatures follow established conventions
- [ ] Migration structure follows column ordering rules
- [ ] Factory methods align with existing state patterns
- [ ] Tests follow established assertion and naming patterns

**Remember**: Consistency across the codebase is more important than individual preferences. Always match existing patterns.

> Note: All Eloquent model tests live under `tests/Unit/`. Do not place model tests under `tests/Feature/`. Feature tests are reserved for HTTP routes, middleware, controllers, page flows, Livewire UI, etc.

### Step 1: Generate Model Files

Create model with migration, factory, and test in one command:
```bash
php artisan make:model ModelName -mf --pest --no-interaction
```

This creates:
- `app/Models/ModelName.php`
- `database/migrations/xxxx_create_model_names_table.php`
- `database/factories/ModelNameFactory.php`
- `tests/Unit/ModelNameTest.php` (All model tests must be in `tests/Unit/`)

### Step 2: Update Migration File

### Required Changes:
1. Add strict typing after opening PHP tag
2. Follow column ordering convention

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->uuid('uuid')->unique();
            $table->string('name');
            // ... other custom fields
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

### Column Ordering Rules:
1. `$table->id()` - Always first
2. `$table->timestamps()` - Always second
3. **Empty line** - Visual separator
4. UUID and custom fields after the separator

### Step 3: Update Model File

### Required Changes:
1. Add strict typing
2. Import and use required traits
3. Set guarded attributes
4. Implement uniqueIds method
5. Set up casts for date fields (using immutable_date for date fields)

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelName extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ModelNameFactory> */
    use HasFactory, HasUuids, LogsModelActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'uuid'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Always use immutable_date for date fields to prevent mutation issues
            'start_date' => 'immutable_date',
            'end_date' => 'immutable_date',
            // For datetime fields, use immutable_datetime
            'published_at' => 'immutable_datetime',
        ];
    }

    /**
     * @return array<array-key, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
```

### Required Traits:
- `HasFactory` - Factory support
- `HasUuids` - Laravel's built-in UUID generation (UUIDv7)
- `LogsModelActivity` - Application-specific activity logging

### Security Notes:
- **Always include `uuid` in `$guarded`** to prevent mass assignment
- Never manually generate UUIDs - let Laravel handle it

### Step 4: Update Factory File

### Required Changes:
1. Add strict typing
2. Implement meaningful fake data

```php
<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModelName>
 */
class ModelNameFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Name',
            // ... other realistic fake data
        ];
    }
}
```

### Step 5: Run Migration

```bash
php artisan migrate --no-interaction
```

### Step 6: Update Unit Tests (tests/Unit)

The test file was already created in Step 1. Update it with the required test coverage:
```php
<?php

declare(strict_types=1);

use App\Concerns\LogsModelActivity;
use App\Models\ModelName;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

it('returns correct unique ids', function () {
    $model = new ModelName;
    expect($model->uniqueIds())->toBe(['uuid']);
});

it('has correct table name', function () {
    expect((new ModelName)->getTable())->toBe('model_names');
    expect(Schema::hasTable('model_names'))->toBeTrue();
});

it('uses correct traits', function () {
    expect(ModelName::class)->toUseTraits([
        HasFactory::class,
        HasUuids::class,
        LogsModelActivity::class,
    ]);
});

it('has correct guarded attributes', function () {
    $model = new ModelName;
    expect($model->getGuarded())->toBe(['id', 'created_at', 'updated_at', 'uuid']);
});

it('can be created in the database', function () {
    $model = ModelName::factory()->create();

    expect($model)->toBeInstanceOf(ModelName::class);
    expect($model->id)->not->toBeNull();
    expect($model->uuid)->not->toBeNull();
    expect($model->created_at)->not->toBeNull();
    expect($model->updated_at)->not->toBeNull();

    $this->assertDatabaseHas('model_names', [
        'id' => $model->id,
        'uuid' => $model->uuid,
    ]);
});

it('can be saved to database', function () {
    $model = ModelName::factory()->create(['name' => 'Test Name']);

    $this->assertDatabaseHas('model_names', [
        'name' => 'Test Name',
    ]);
});
```

### Step 7: Code Formatting

Format code to match project standards:
```bash
./vendor/bin/pint
```

### Step 8: Run Tests

Test the new model:
```bash
php artisan test --filter=ModelName
```

Run full test suite to ensure no regressions:
```bash
composer test
```

## File Reference

Quick lookup of relevant files for model creation:

| File Type | Location | Purpose |
|-----------|----------|---------|
| Model | `app/Models/ModelName.php` | Eloquent model class |
| Migration | `database/migrations/xxxx_create_model_names_table.php` | Database table structure |
| Factory | `database/factories/ModelNameFactory.php` | Test data generation |
| Unit Test | `tests/Unit/ModelNameTest.php` | Model structure and behavior tests |
| UUID Conventions | `app/Models/CLAUDE.md` | UUID implementation guidelines |
| Migration Conventions | `database/migrations/CLAUDE.md` | Database migration patterns |

## Testing Checklist

Before considering the model complete, verify:

### Consistency Verification
- [ ] **Step 0 completed** - Existing models and patterns studied thoroughly
- [ ] Model implementation matches established patterns in the codebase
- [ ] Migration follows exact column ordering from existing migrations
- [ ] Factory states and methods align with existing factory patterns
- [ ] Tests follow established assertion and naming conventions

### Technical Implementation
- [ ] Strict typing declared in all files
- [ ] Migration follows column ordering convention
- [ ] Model uses all required traits (HasFactory, HasUuids, LogsModelActivity)
- [ ] UUID field is in `$guarded` array
- [ ] Factory produces realistic test data
- [ ] All unit tests pass
- [ ] Code passes Pint formatting
- [ ] **Full test suite passes without regressions** (`composer test`)
- [ ] Migration runs successfully

## Common Issues & Solutions

### Issue: UUID not generating automatically
**Solution**: Ensure `HasUuids` trait is used and `uniqueIds()` method returns `['uuid']`

### Issue: Mass assignment exception for UUID
**Solution**: Add `'uuid'` to the `$guarded` array in the model

### Issue: Tests failing due to database connection
**Solution**: Unit tests with database operations need `LazilyRefreshDatabase` trait (auto-applied in this project)

### Issue: Pint formatting failures
**Solution**: Run `./vendor/bin/pint` to fix formatting issues before committing

## Best Practices

### Security
- UUID fields must always be in `$guarded` array to prevent mass assignment vulnerabilities
- Never allow mass assignment of auto-generated fields (id, timestamps, uuid)

### Code Structure
- Always use `declare(strict_types=1);` for type safety
- Follow established trait usage patterns consistently
- Use PHPDoc type annotations: `array<array-key, string>`

### Date Handling
- Always use `immutable_date` or `immutable_datetime` for date fields in the `casts()` method
- This prevents accidental mutation of date objects and ensures consistent behavior
- Example: `'start_date' => 'immutable_date'` will cast to `Carbon\CarbonImmutable`
- Never use mutable Carbon instances for date fields to prevent unexpected side effects

### Database Design
- ID first, timestamps second, then custom fields for predictable column ordering
- Empty line separates framework fields from custom fields for readability
- Use Laravel's built-in UUID support (UUIDv7) for performance

### Testing Strategy
- Write comprehensive unit test coverage for both structure and behavior
- Test model traits, guarded attributes, and database operations
- Use Pest testing framework conventions throughout

### Performance
- UUIDv7 provides better database indexing performance than UUIDv4
- Factory-generated test data should be realistic to catch edge cases

## Related Documentation

- [`/app/Models/CLAUDE.md`](../app/Models/CLAUDE.md) - UUID implementation conventions
- [`/database/migrations/CLAUDE.md`](../database/migrations/CLAUDE.md) - Migration column ordering
- [`/CLAUDE.md`](../../CLAUDE.md) - Overall project guidelines
