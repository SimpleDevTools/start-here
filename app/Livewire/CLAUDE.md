# Livewire Component Guidelines

## Component Structure

### Page Components (Full-Page Routes)
When creating Livewire components that are used as full-page routes:

1. **No explicit layout declaration needed** - Livewire automatically uses the default app layout
   ```php
   // ✅ Good - Clean and simple
   #[Title('Projects')]
   class Index extends Component
   {
       public function render(): View
       {
           return view('livewire.projects.index');
       }
   }

   // ❌ Avoid - Unnecessary layout attribute
   #[Layout('components.layouts.app')]
   #[Title('Projects')]
   class Index extends Component
   ```

2. **Use Title attribute** - Always add the `#[Title('Page Name')]` attribute for proper page titles

3. **Return type hints** - Always specify return type for the render method: `\Illuminate\Contracts\View\View`

### Route Registration
When registering Livewire components in routes:

```php
// ✅ Good - Import namespace, use direct reference
use App\Livewire\Projects;
Route::get('projects', Projects\Index::class)->name('projects.index');

// ❌ Avoid - Unnecessary aliasing
use App\Livewire\Projects\Index as ProjectsIndex;
Route::get('projects', ProjectsIndex::class)->name('projects.index');
```

### Component Organization
- Group related components in subdirectories (e.g., `Projects/`, `Settings/`)
- Use `Index` for main listing/dashboard components
- Follow RESTful naming when applicable (Index, Create, Edit, Show)

## Strict Typing
Always use strict typing at the top of every PHP file:
```php
<?php

declare(strict_types=1);
```

## Testing
Every Livewire component must have corresponding tests with:
- Guest access test (should redirect to login)
- Authenticated user access test
- Component render test