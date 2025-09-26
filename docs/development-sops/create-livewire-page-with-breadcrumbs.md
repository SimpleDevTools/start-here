# SOP: Creating Livewire Pages with Breadcrumbs

## Purpose
This SOP documents how to create new Livewire page components that use the page layout with breadcrumb navigation in this project, which uses Laravel v12, Livewire v3, and Flux UI.

## Prerequisites
- Familiarity with Laravel routing (Laravel v12 structure)
- Understanding of Livewire class-based components
- Knowledge of Blade templating and slot usage
- Access to the application codebase

## Page Layout Overview

This project uses a structured page layout system with breadcrumbs:
- **App Layout**: Basic sidebar + main content area (`components.layouts.app`)
- **Page Layout**: Enhanced layout with breadcrumbs, title, description (`components.layouts.page`)

Use the Page Layout for all user-facing pages that need navigation context.

## Step-by-Step Process

### Step 1: Generate the Livewire Component

Use the Livewire generator to scaffold a component with tests:

```bash
php artisan livewire:make Analytics/Dashboard --pest --no-interaction
```

This creates:
- `app/Livewire/Analytics/Dashboard.php`
- `resources/views/livewire/analytics/dashboard.blade.php`
- `tests/Feature/Livewire/Analytics/DashboardTest.php`

### Step 2: Configure the Livewire Component Class

Update the component to use the page layout with proper attributes:

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Analytics;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.page')]
#[Title('Analytics Dashboard')]
class Dashboard extends Component
{
    public function render(): View
    {
        return view('livewire.analytics.dashboard');
    }
}
```

**Key Points:**
- Use `#[Layout('components.layouts.page')]` for pages with breadcrumbs
- Always include `#[Title('Page Name')]` for proper page titles
- Import both Layout and Title attributes

### Step 3: Create the Page View with Breadcrumbs

The view must define the required slots for the page layout:

```blade
<x-slot:title>{{ __('Analytics Dashboard') }}</x-slot:title>
<x-slot:description>{{ __('View comprehensive analytics and insights.') }}</x-slot:description>
<x-slot:breadcrumbs>
    <flux:breadcrumbs.item>
        <div class="flex items-center gap-2">
            <flux:icon icon="chart-bar" />
            {{ __('Analytics') }}
        </div>
    </flux:breadcrumbs.item>
    <flux:breadcrumbs.item>{{ __('Dashboard') }}</flux:breadcrumbs.item>
</x-slot:breadcrumbs>

<div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Your page content here -->
    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
</div>
```

**Required Slots:**
- `title`: Page heading (shown in layout)
- `description`: Page subtitle/description
- `breadcrumbs`: Navigation breadcrumbs

### Step 4: Breadcrumb Patterns

#### Standalone Pages
For single-level pages (like Maps, Time Matrix):

```blade
<x-slot:breadcrumbs>
    <flux:breadcrumbs.item>
        <div class="flex items-center gap-2">
            <flux:icon icon="map" />
            {{ __('Maps') }}
        </div>
    </flux:breadcrumbs.item>
</x-slot:breadcrumbs>
```

#### Grouped Pages
For pages within a section (like Financial > Invoices):

```blade
<x-slot:breadcrumbs>
    <flux:breadcrumbs.item>
        <div class="flex items-center gap-2">
            <flux:icon icon="currency-dollar" />
            {{ __('Financial') }}
        </div>
    </flux:breadcrumbs.item>
    <flux:breadcrumbs.item>{{ __('Invoices') }}</flux:breadcrumbs.item>
</x-slot:breadcrumbs>
```

#### Settings Pages
For settings pages with special handling:

```blade
<x-slot:breadcrumbs>
    <flux:breadcrumbs.item href="{{ route('settings.profile') }}">{{ __('Settings') }}</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>{{ __('Appearance') }}</flux:breadcrumbs.item>
</x-slot:breadcrumbs>
```

### Step 5: Icon Consistency

Icons in breadcrumbs MUST match the sidebar navigation icons. Reference `/resources/views/components/layouts/app/sidebar.blade.php` for the correct icons:

| Section | Icon | Usage |
|---------|------|-------|
| People | `users` | Teams, Contacts, Clients |
| Projects | `briefcase` | Project pages |
| Tasks | `clipboard-document-check` | Task management |
| Resources | `cube` | Resource catalog |
| Scheduling | `calendar` | Team schedule, Gantt |
| Maps | `map` | Map views |
| Financial | `currency-dollar` | Invoices, Transactions |
| Time Matrix | `clock` | Time tracking |

### Step 6: Define the Route

Add the route in the appropriate file (usually `/routes/web.php`):

```php
use App\Livewire\Analytics\Dashboard as AnalyticsDashboard;

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::get('analytics/dashboard', AnalyticsDashboard::class)->name('analytics.dashboard');
});
```

### Step 7: Add to Sidebar Navigation

Update `/resources/views/components/layouts/app/sidebar.blade.php`:

```blade
<flux:navlist.group
    class="grid"
    :heading="__('Analytics')"
    icon="chart-bar"
    expandable
    :expanded="request()->routeIs('analytics.*')"
>
    <flux:navlist.item
        :href="route('analytics.dashboard')"
        :current="request()->routeIs('analytics.dashboard')"
        wire:navigate
    >
        {{ __('Dashboard') }}
    </flux:navlist.item>
</flux:navlist.group>
```

### Step 8: Create Tests

Create comprehensive tests in `/tests/Feature/Livewire/Analytics/DashboardTest.php`:

```php
<?php

declare(strict_types=1);

use App\Livewire\Analytics\Dashboard as AnalyticsDashboard;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('redirects guests to login', function () {
    get(route('analytics.dashboard'))->assertRedirect();
});

it('renders for authenticated users', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('analytics.dashboard'))
        ->assertOk()
        ->assertSeeLivewire(AnalyticsDashboard::class);

    visit(route('analytics.dashboard'))
        ->assertNoSmoke();
});

it('livewire component returns 200', function () {
    Livewire::test(AnalyticsDashboard::class)->assertStatus(200);
});

it('displays correct title and breadcrumbs', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('analytics.dashboard'))
        ->assertSee('Analytics Dashboard')
        ->assertSee('Analytics')
        ->assertSee('Dashboard');
});
```

## File Reference

| File | Purpose |
|------|---------|
| `/resources/views/components/layouts/page.blade.php` | Page layout with breadcrumbs |
| `/resources/views/components/layouts/app/sidebar.blade.php` | Sidebar navigation (icon reference) |
| `/app/Livewire/*` | Livewire component classes |
| `/resources/views/livewire/*` | Livewire component views |
| `/routes/web.php` | Route definitions |
| `/tests/Feature/Livewire/*` | Feature tests |

## Testing Checklist

- [ ] Component uses `#[Layout('components.layouts.page')]`
- [ ] Component has `#[Title('Page Name')]` attribute
- [ ] View defines all required slots (title, description, breadcrumbs)
- [ ] Breadcrumb icons match sidebar navigation
- [ ] Route is properly authenticated
- [ ] Tests include guest redirect, authenticated access, and smoke test
- [ ] All tests pass (`composer test`)
- [ ] Breadcrumbs display correctly in browser
- [ ] Page title appears in browser tab

## Common Issues & Solutions

**Breadcrumbs not showing:**
- Verify all three slots are defined (title, description, breadcrumbs)
- Check that the view uses correct slot syntax: `<x-slot:name>`

**Icons not displaying:**
- Confirm icon name matches Flux UI icons
- Check that icon is wrapped in the flex container structure

**Page layout not applied:**
- Ensure `#[Layout('components.layouts.page')]` is used
- Verify the layout attribute is properly imported

**Breadcrumb styling issues:**
- Run `./node_modules/.bin/blade-formatter "resources/**/*.blade.php" --write`
- Check that icon structure matches the pattern

## Best Practices

1. **Consistency**: Always match breadcrumb icons with sidebar icons
2. **Translations**: Use `{{ __('Text') }}` for all user-facing text
3. **Structure**: Follow the established breadcrumb patterns for different page types
4. **Testing**: Include comprehensive tests for both functionality and presentation
5. **Accessibility**: Ensure breadcrumbs provide proper navigation context

## Version History
- v1.0 - Initial breadcrumbs implementation (2025-01-31)