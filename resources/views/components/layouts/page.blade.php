<x-layouts.app :title="$title ?? null">
    <div class="mb-6 flex flex-col gap-2">
        <flux:breadcrumbs>
            {{ $breadcrumbs }}
        </flux:breadcrumbs>
        <div class="flex">
            <div class="flex flex-col gap-2">
                <div class="mt-4 flex items-center gap-2">
                    <flux:heading
                        size="xl"
                        level="1"
                    >
                        {{ $title }}
                    </flux:heading>
                </div>
                <flux:subheading
                    class="mb-6"
                    size="lg"
                >
                    {{ $description }}
                </flux:subheading>
            </div>
            <div class="ml-auto">{{ $actions ?? null }}</div>
        </div>
        <flux:separator variant="subtle" />
    </div>
    {{ $slot }}
</x-layouts.app>
