<!DOCTYPE html>
<html
    class="dark"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header
        class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
        container
    >
        <flux:sidebar.toggle
            class="lg:hidden"
            icon="bars-2"
            inset="left"
        />

        <a
            class="me-5 ms-2 flex items-center space-x-2 lg:ms-0 rtl:space-x-reverse"
            href="{{ route('dashboard') }}"
            wire:navigate
        >
            <x-app-logo />
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item
                icon="layout-grid"
                :href="route('dashboard')"
                :current="request()->routeIs('dashboard')"
                wire:navigate
            >
                {{ __('Dashboard') }}
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="py-0! me-1.5 space-x-0.5 rtl:space-x-reverse">
            <flux:tooltip
                :content="__('Search')"
                position="bottom"
            >
                <flux:navbar.item
                    class="!h-10 [&>div>svg]:size-5"
                    href="#"
                    icon="magnifying-glass"
                    :label="__('Search')"
                />
            </flux:tooltip>
            <flux:tooltip
                :content="__('Repository')"
                position="bottom"
            >
                <flux:navbar.item
                    class="h-10 max-lg:hidden [&>div>svg]:size-5"
                    href="https://github.com/laravel/livewire-starter-kit"
                    icon="folder-git-2"
                    target="_blank"
                    :label="__('Repository')"
                />
            </flux:tooltip>
            <flux:tooltip
                :content="__('Documentation')"
                position="bottom"
            >
                <flux:navbar.item
                    class="h-10 max-lg:hidden [&>div>svg]:size-5"
                    href="https://laravel.com/docs/starter-kits#livewire"
                    icon="book-open-text"
                    target="_blank"
                    label="Documentation"
                />
            </flux:tooltip>
        </flux:navbar>

        <!-- Desktop User Menu -->
        <flux:dropdown
            position="top"
            align="end"
        >
            <flux:profile
                class="cursor-pointer"
                :initials="auth()->user()->initials()"
            />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                >
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item
                        :href="route('settings.profile')"
                        icon="cog"
                        wire:navigate
                    >{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form
                    class="w-full"
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf
                    <flux:menu.item
                        class="w-full"
                        type="submit"
                        as="button"
                        icon="arrow-right-start-on-rectangle"
                    >
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar
        class="border-e border-zinc-200 bg-zinc-50 lg:hidden dark:border-zinc-700 dark:bg-zinc-900"
        stashable
        sticky
    >
        <flux:sidebar.toggle
            class="lg:hidden"
            icon="x-mark"
        />

        <a
            class="ms-1 flex items-center space-x-2 rtl:space-x-reverse"
            href="{{ route('dashboard') }}"
            wire:navigate
        >
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')">
                <flux:navlist.item
                    icon="layout-grid"
                    :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate
                >
                    {{ __('Dashboard') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item
                href="https://github.com/laravel/livewire-starter-kit"
                icon="folder-git-2"
                target="_blank"
            >
                {{ __('Repository') }}
            </flux:navlist.item>

            <flux:navlist.item
                href="https://laravel.com/docs/starter-kits#livewire"
                icon="book-open-text"
                target="_blank"
            >
                {{ __('Documentation') }}
            </flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    {{ $slot }}

    @fluxScripts
</body>

</html>
