# SOP: Adding a New Menu Item to the Sidebar

## Purpose
This SOP documents how to add new navigation items to the application sidebar in this project, which uses Laravel v12, Livewire v3, and Flux UI.

## Prerequisites
- Familiarity with Laravel routing (Laravel v12 structure)
- Standard Livewire class-based components
- Access to the application codebase

## Menu Item Types

### 1. Simple Menu Item (No Sub-items)
A standalone link that navigates directly to a page.

### 2. Grouped/Collapsible Menu (With Sub-items)
A parent section containing sub-items. The parent is a label/toggle; sub-items are individual links.

## Step-by-Step Process

### Step 1: Generate a Livewire Component

Use the Livewire generator to scaffold a component class and Blade view.

```bash
php artisan livewire:make Reports/Index --pest --no-interaction
```

This creates:
- `app/Livewire/Reports/Index.php`
- `resources/views/livewire/reports/index.blade.php`
- `tests/Feature/Livewire/Reports/IndexTest.php` (because of `--pest`)

Example component class:

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Reports')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.reports.index');
    }
}
```

Example component view (`resources/views/livewire/reports/index.blade.php`):

```blade
<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">{{ __('Reports') }}</h1>
        <p class="text-zinc-500 dark:text-zinc-400">{{ __('Overview of key metrics and exports.') }}</p>
    </div>

    <div class="relative min-h-[60vh] flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
    </div>
</div>
```

### Step 2: Define the Route (Backend)

Location: `/routes/web.php` (or an appropriate route file). Use the existing authenticated middleware.

```php
<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;
use App\Livewire\Reports\Index as ReportsIndex;

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::get('reports', ReportsIndex::class)->name('reports.index');
});
```

### Step 3: Add the Menu Item to the Sidebar

Location: `/resources/views/components/layouts/app/sidebar.blade.php`

- Use Flux UI `navlist` and `navlist.item`.
- Use named routes and `request()->routeIs(...)` for active state.

#### Simple Menu Item

```blade
<flux:navlist.item icon="layout-grid" :href="route('reports.index')" :current="request()->routeIs('reports.index')" wire:navigate>
    {{ __('Reports') }}
</flux:navlist.item>
```

Insert within the appropriate `flux:navlist.group`, e.g., under the existing "Platform" group or a new group.

#### Grouped Items

Use `flux:navlist.group` from `/resources/views/flux/navlist/group.blade.php` (supports headings and expandable state). Example:

```blade
<flux:navlist.group :heading="__('Analytics')" class="grid">
    <flux:navlist.item icon="layout-grid" :href="route('reports.index')" :current="request()->routeIs('reports.index')" wire:navigate>
        {{ __('Reports') }}
    </flux:navlist.item>
    <flux:navlist.item icon="book-open-text" :href="route('reports.export')" :current="request()->routeIs('reports.export')" wire:navigate>
        {{ __('Exports') }}
    </flux:navlist.item>
</flux:navlist.group>
```

For each additional sub-item, repeat Step 1 and Step 2 to create a component and route.

### Step 4: Icons

This project uses Flux UI icons and custom Blade icon components under `/resources/views/flux/icon/`.

- Use available icon names like `home`, `folder-git-2`, `book-open-text`, `layout-grid` via `icon="..."`.
- To add a new icon, add a Blade component under `resources/views/flux/icon/` and reference it by its file name.

### Step 5: Tests (Required)

Create feature tests with Pest to verify routing, auth, and Livewire rendering.

Location: `/tests/Feature/Livewire/Reports/IndexTest.php`

```php
<?php

declare(strict_types=1);

use App\Livewire\Reports\Index as ReportsIndex;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('redirects guests to login', function () {
    get(route('reports.index'))->assertRedirect();
});

it('renders for authenticated users', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('reports.index'))
        ->assertOk()
        ->assertSeeLivewire(ReportsIndex::class);

    visit(route('reports.index'))
        ->assertNoSmoke();
});

it('livewire component returns 200', function () {
    Livewire::test(ReportsIndex::class)->assertStatus(200);
});
```

### Step 6: Manual Checklist

- [ ] Route is registered within the authenticated middleware group
- [ ] Livewire component exists under `app/Livewire/...`
- [ ] Component view exists under `resources/views/livewire/...`
- [ ] Component sets layout to `components.layouts.app`
- [ ] Sidebar item appears under the correct group
- [ ] Active state highlights correctly (`:current` binding)
- [ ] Icon renders correctly
- [ ] Tests pass (`php artisan test`)

## File Reference

| File | Purpose |
|------|---------|
| `/resources/views/components/layouts/app/sidebar.blade.php` | Sidebar UI and nav items |
| `/resources/views/flux/navlist/group.blade.php` | Grouping/collapsible behavior for nav sections |
| `/routes/web.php` and `/routes/settings.php` | Route definitions |
| `/app/Livewire/*` | Livewire component classes |
| `/resources/views/livewire/*` | Livewire component views |
| `/tests/Feature/Livewire/*` | Livewire feature tests |

## Common Issues & Solutions

- **Menu item not showing**: Ensure the item is inside a `flux:navlist` and correct group.
- **Route not working**: Verify it is inside the authenticated middleware group and named.
- **Active state not working**: Check `request()->routeIs('name')` matches the route name.
- **Icon missing**: Confirm icon component exists under `resources/views/flux/icon/` or use an existing one.

## Version History
- v1.0 - Initial project version (Livewire + Flux UI) (2025-08-22)