# Creating Modals

This SOP provides comprehensive guidance for implementing modals in the GO application using our established patterns and conventions.

## Architecture Overview

Our modal implementation uses a combination of:
- **Flux UI Pro**: Modal components and styling
- **Wire Elements Pro**: Server-side modal management for Livewire
- **Custom wrapper**: `<x-livewire-modal>` component for consistent behavior
- **Alpine.js**: Client-side modal triggers and interactions

## Modal Component Structure

### 1. PHP Livewire Component

Create the Livewire component extending `WireElements\Pro\Components\Modal\Modal`:

```php
<?php

declare(strict_types=1);

namespace App\Livewire\[Section]\[Feature];

use App\Models\[Model];
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Smpita\TypeAs\TypeAs;
use WireElements\Pro\Components\Modal\Modal;

class [ComponentName] extends Modal
{
    // Properties with validation attributes
    #[Validate('required|string|max:255')]
    public string $name = '';

    // For edit/delete modals, accept ID parameter
    public string $[model]Uuid = '';

    public function render(): View
    {
        return view('livewire.[section].[feature].[component-name]');
    }

    public function submit(): void
    {
        $this->authorize('[action]', [Model]::class);

        $validated = TypeAs::array($this->validate());

        // Business logic here

        $this->close();
        $this->reset();
        $this->dispatch('[section].[action]', [model]: $[model]);
    }
}
```

### 2. Blade View Template

Use the custom `<x-livewire-modal>` wrapper with appropriate content:

```blade
<x-livewire-modal name="[section].[feature].[component-name]">
    <form
        class="space-y-6"
        data-test="[component-name]-form"
        wire:submit="submit"
    >
        <div>
            <flux:heading size="lg">{{ __('Modal Title') }}</flux:heading>
            <flux:text class="mt-2">{{ __('Modal description or instructions.') }}</flux:text>
        </div>

        <!-- Form fields here -->
        <flux:input
            data-test="[field]-input"
            label="{{ __('Field Label') }}"
            placeholder="{{ __('Field placeholder') }}"
            wire:model="[field]"
        />

        <!-- Action buttons -->
        <div class="flex">
            <flux:spacer />
            <flux:button
                variant="ghost"
                wire:click="$dispatch('modal.close', '[section].[feature].[component-name]')"
            >
                {{ __('Cancel') }}
            </flux:button>
            <flux:button
                data-test="[component-name]-submit"
                type="submit"
                variant="primary"
            >
                {{ __('Action Button') }}
            </flux:button>
        </div>
    </form>
</x-livewire-modal>
```

## Modal Trigger Patterns

### Pattern 1: Simple Modal (No Parameters)

**Trigger Button:**
```blade
<flux:button
    data-test="create-[model]-button"
    x-open-modal="'[section].[feature].[component-name]'"
>
    {{ __('Create [Model]') }}
</flux:button>
```

### Pattern 2: Modal with Parameters

**Trigger Button:**
```blade
<flux:menu.item
    icon="pencil-square"
    wire:click="$dispatch('modal.open', {component: '[section].[feature].[component-name]', arguments: {'[model]Uuid': '{{ $[model]->uuid }}'}})"
>
    {{ __('Edit') }}
</flux:menu.item>
```

**Component with Parameters:**
```php
public string $[model]Uuid = '';

public function mount(): void
{
    // Initialize with existing data for edit modals
    $this->name = $this->[model]->name;
}

#[Computed()]
public function [model](): [Model]
{
    return currentCompany()->[models]()->where('uuid', $this->[model]Uuid)->firstOrFail();
}
```

## Common Modal Patterns

### 1. Create Modal

**Features:**
- Form with input fields
- Validation
- Success event dispatch
- Reset form on success

**Example Structure:**
```php
class Create extends Modal
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    public function submit(): void
    {
        $this->authorize('create', [Model]::class);
        
        $validated = TypeAs::array($this->validate());
        
        $[model] = currentCompany()->[models]()->create($validated);
        
        $this->close();
        $this->reset();
        $this->dispatch('[section].created', [model]: $[model]);
    }
}
```

### 2. Edit Modal

**Features:**
- Pre-populate form with existing data
- Update existing record
- Parameter-based model resolution

**Example Structure:**
```php
class Edit extends Modal
{
    public string $[model]Uuid;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public function mount(): void
    {
        $this->name = $this->[model]->name;
    }

    #[Computed()]
    public function [model](): [Model]
    {
        return currentCompany()->[models]()->where('uuid', $this->[model]Uuid)->firstOrFail();
    }

    public function submit(): void
    {
        $this->authorize('update', $this->[model]);
        
        $validated = TypeAs::array($this->validate());
        
        $this->[model]->update($validated);
        
        $this->close();
        $this->dispatch('[section].updated', [model]: $this->[model]);
    }
}
```

### 3. Delete/Confirmation Modal

**Features:**
- Confirmation dialog
- Danger styling
- No form validation needed
- Custom sizing (typically smaller)

**Blade Template:**
```blade
<x-livewire-modal
    class="max-w-md"
    name="[section].[feature].delete"
>
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Delete [model]?') }}</flux:heading>
            <flux:text class="mt-2">
                <p>{{ __('You\'re about to delete') }} <strong>{{ $this->[model]->name }}</strong>.</p>
                <p>{{ __('This action cannot be reversed.') }}</p>
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer />
            <flux:button
                variant="ghost"
                wire:click="$dispatch('modal.close', '[section].[feature].delete')"
            >
                {{ __('Cancel') }}
            </flux:button>
            <flux:button
                data-test="delete-[model]-confirm"
                type="button"
                variant="danger"
                wire:click="delete"
            >
                {{ __('Delete [model]') }}
            </flux:button>
        </div>
    </div>
</x-livewire-modal>
```

**Component:**
```php
class Delete extends Modal
{
    public string $[model]Uuid;

    #[Computed()]
    public function [model](): [Model]
    {
        return currentCompany()->[models]()->where('uuid', $this->[model]Uuid)->firstOrFail();
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->[model]);

        $[model]Name = $this->[model]->name;
        $this->[model]->delete();

        $this->close();
        $this->dispatch('[section].deleted', [model]Name: $[model]Name);
    }
}
```

## Modal Sizing and Styling

### Default Sizing
The `<x-livewire-modal>` wrapper applies default sizing:
```blade
class="md:w-3xl max-w-3xl"
```

### Custom Sizing Examples
```blade
<!-- Small modal (confirmations) -->
<x-livewire-modal class="max-w-md" name="confirm-delete">

<!-- Medium modal -->
<x-livewire-modal class="md:w-xl max-w-xl" name="edit-item">

<!-- Large modal -->
<x-livewire-modal class="md:w-4xl max-w-4xl" name="detailed-form">

<!-- Full width -->
<x-livewire-modal class="md:w-full max-w-full" name="large-table">
```

## Form Patterns

### Input Fields
```blade
<flux:input
    data-test="[field]-input"
    label="{{ __('Field Label') }}"
    placeholder="{{ __('Enter [field]...') }}"
    wire:model="[field]"
/>
```

### Select/Dropdown Fields
```blade
<flux:select
    label="{{ __('Select Option') }}"
    wire:model="[field]"
>
    <option value="">{{ __('Choose option...') }}</option>
    @foreach($options as $option)
        <option value="{{ $option->id }}">{{ $option->name }}</option>
    @endforeach
</flux:select>
```

### Checkbox Groups
```blade
<flux:fieldset>
    <flux:legend>{{ __('Selection Options') }}</flux:legend>
    <flux:text class="text-gray-600 dark:text-gray-400" size="sm">
        {{ __('Select applicable options') }}
    </flux:text>
    <flux:checkbox.group
        class="mt-4 space-y-2"
        wire:model="selectedOptions"
    >
        @foreach ($options as $option)
            <flux:checkbox
                value="{{ $option->id }}"
                label="{{ $option->name }}"
            />
        @endforeach
    </flux:checkbox.group>
</flux:fieldset>
```

### Action Button Patterns
```blade
<!-- Single action (Create modals) -->
<div class="flex">
    <flux:spacer />
    <flux:button
        data-test="[action]-submit"
        type="submit"
        variant="primary"
    >
        {{ __('Create [Model]') }}
    </flux:button>
</div>

<!-- Two actions (Edit modals) -->
<div class="flex">
    <flux:spacer />
    <flux:button
        variant="ghost"
        wire:click="$dispatch('modal.close', '[modal-name]')"
    >
        {{ __('Cancel') }}
    </flux:button>
    <flux:button
        data-test="[action]-submit"
        type="submit"
        variant="primary"
    >
        {{ __('Save changes') }}
    </flux:button>
</div>

<!-- Danger actions (Delete modals) -->
<div class="flex gap-2">
    <flux:spacer />
    <flux:button
        variant="ghost"
        wire:click="$dispatch('modal.close', '[modal-name]')"
    >
        {{ __('Cancel') }}
    </flux:button>
    <flux:button
        data-test="[action]-confirm"
        type="button"
        variant="danger"
        wire:click="[action]"
    >
        {{ __('Delete [model]') }}
    </flux:button>
</div>
```

## Event Communication

### Dispatch Events from Modals
```php
// Success events
$this->dispatch('[section].created', [model]: $[model]);
$this->dispatch('[section].updated', [model]: $[model]);
$this->dispatch('[section].deleted', [model]Name: $[model]Name);

// Error events (if needed)
$this->dispatch('[section].error', message: 'Error message');
```

### Listen for Events in Parent Components
```php
#[On('[section].created')]
public function handle[Model]Created([Model] $[model]): void
{
    // Refresh data, show notification, etc.
    $this->resetPage();
}
```

## Testing Guidelines

### Unit Tests
Every modal component should have unit tests covering:
- Component instantiation
- Mount method (for edit/delete modals)
- Submit/action methods
- Authorization checks
- Event dispatching

**Example Test Structure:**
```php
it('can create [model]', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test([Section]\[Feature]\Create::class)
        ->set('name', 'Test [Model]')
        ->call('submit')
        ->assertDispatched('[section].created');
    
    expect([Model]::where('name', 'Test [Model]')->exists())->toBeTrue();
});

it('requires name field', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test([Section]\[Feature]\Create::class)
        ->set('name', '')
        ->call('submit')
        ->assertHasErrors(['name' => 'required']);
});
```

### Browser Tests
Include browser tests for modal interactions:
```php
it('can open and close modal', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->visit('/[section]/[feature]')
        ->click('[data-test="create-[model]-button"]')
        ->assertSee('Create [Model]')
        ->click('Cancel')
        ->assertDontSee('Create [Model]');
});
```

## Best Practices

### 1. Naming Conventions
- **Component names**: Use descriptive names (Create, Edit, Delete, etc.)
- **Modal names**: Use dot notation matching component namespace
- **Data test attributes**: Consistently name test selectors

### 2. Authorization
Always include authorization checks:
```php
$this->authorize('[action]', [Model]::class);
$this->authorize('[action]', $this->[model]);
```

### 3. TypeAs Usage
Use TypeAs for validation result coercion:
```php
$validated = TypeAs::array($this->validate());
```

### 4. Event Naming
Use consistent event naming patterns:
- `[section].[action]` (e.g., 'teams.created', 'projects.updated')
- Pass relevant data with events

### 5. Dark Mode Support
Ensure all colors support dark mode:
```blade
class="text-gray-600 dark:text-gray-400"
```

### 6. Accessibility
- Include proper form labels
- Use semantic HTML structure
- Provide clear button text
- Include appropriate ARIA attributes (handled by Flux components)

### 7. Loading States
Consider adding loading states for submit actions:
```blade
<flux:button
    type="submit"
    variant="primary"
    wire:loading.attr="disabled"
>
    <span wire:loading.remove>{{ __('Create [Model]') }}</span>
    <span wire:loading>{{ __('Creating...') }}</span>
</flux:button>
```

## File Organization

Follow the established directory structure:
```
app/Livewire/[Section]/[Feature]/
├── Create.php
├── Edit.php
├── Delete.php
└── RemoveUser.php (for relationship-specific actions)

resources/views/livewire/[section]/[feature]/
├── create.blade.php
├── edit.blade.php
├── delete.blade.php
└── remove-user.blade.php

tests/Feature/Livewire/[Section]/[Feature]/
├── CreateTest.php
├── EditTest.php
├── DeleteTest.php
└── RemoveUserTest.php
```

## Complete Example

See the existing team management modals for complete examples:
- `app/Livewire/People/Teams/Create.php`
- `app/Livewire/People/Teams/Edit.php`
- `app/Livewire/People/Teams/Delete.php`
- `resources/views/livewire/people/teams/create.blade.php`
- `resources/views/livewire/people/teams/edit.blade.php`
- `resources/views/livewire/people/teams/delete.blade.php`

These provide real-world examples of all patterns documented in this SOP.