# Livewire View Guidelines

## View Structure

### Page Component Views
For Livewire components that serve as full pages:

1. **Single root element** - Livewire requires exactly one root element
   ```blade
   {{-- ✅ Good - Single root div --}}
   <div>
       <!-- Content here -->
   </div>

   {{-- ❌ Bad - Multiple root elements or using layout component --}}
   <x-layouts.app>
       <div>...</div>
   </x-layouts.app>
   ```

2. **No layout components in views** - The layout is handled by Livewire automatically for page components

## Layout Best Practices

### Spacing and Padding
- **Container padding**: Use `p-4` on the root container for consistent spacing
- **Gap utilities**: Use `gap-4` for spacing between child elements instead of margins
- **Full height utilization**: Use `h-full` and `flex-1` to properly fill available space

### Content Structure Pattern
```blade
<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
    {{-- Header Section --}}
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">{{ __('Page Title') }}</h1>
        <p class="text-zinc-500 dark:text-zinc-400">{{ __('Page description.') }}</p>
    </div>

    {{-- Main Content Area --}}
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        {{-- Content or placeholder --}}
    </div>
</div>
```

### Height Management
- **Full height patterns**: Use `h-full` instead of fixed viewport heights like `min-h-[60vh]`
- **Flex grow**: Use `flex-1` to allow content areas to expand and fill remaining space
- **Consistent borders**: Apply border styling consistently across content areas

## Dark Mode Support
Always include dark mode variants for colors:
- `text-zinc-500 dark:text-zinc-400` for secondary text
- `border-neutral-200 dark:border-neutral-700` for borders
- `stroke-gray-900/20 dark:stroke-neutral-100/20` for patterns

## Placeholder Patterns
When using placeholder patterns during development:
```blade
<x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
```
- Position absolutely within a relative container
- Use `inset-0 size-full` for full coverage
- Include dark mode stroke colors

## Translations
Always use translation helpers for user-facing text:
```blade
{{ __('Text to translate') }}
```

## Component Organization
- Group related views in subdirectories matching the Livewire component structure
- Keep view names consistent with component names (e.g., `Index.php` → `index.blade.php`)