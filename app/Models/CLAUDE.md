# UUID Conventions for Models

## Using Laravel's Built-in UUID Support

### HasUuids Trait
- Use `Illuminate\Database\Eloquent\Concerns\HasUuids` trait
- Generates UUIDv7 (ordered UUIDs) by default - more efficient for database indexing
- Automatically handles UUID generation during model creation

### Implementation Example
```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{
    use HasUuids;

    protected $guarded = ['id', 'created_at', 'updated_at', 'uuid'];

    /**
     * @return array<array-key, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
```

### Migration Setup
Add a UUID column to the migration:
```php
$table->uuid('uuid')->unique();
```

### Key Points
- Never manually generate UUIDs - let Laravel handle it
- Use `uniqueIds()` method to specify which columns get UUIDs
- **Always add UUID fields to `$guarded` array** to prevent mass assignment
- UUIDv7 is the default (ordered, lexicographically sortable)
- Use `HasVersion4Uuids` trait if you need UUID v4 instead
- Type annotations should use `array<array-key, string>` format