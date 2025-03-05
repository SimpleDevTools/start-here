<div class="flex flex-col gap-6">
    <x-auth-header
        title="Create an account"
        description="Enter your details below to create your account"
    />

    <!-- Session Status -->
    <x-auth-session-status
        class="text-center"
        :status="session('status')"
    />

    <form
        class="flex flex-col gap-6"
        wire:submit="register"
    >
        <!-- Name -->
        <flux:input
            id="name"
            name="name"
            type="text"
            wire:model="name"
            :label="__('Name')"
            required
            autofocus
            autocomplete="name"
            placeholder="Full name"
        />

        <!-- Email Address -->
        <flux:input
            id="email"
            name="email"
            type="email"
            wire:model="email"
            :label="__('Email address')"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            id="password"
            name="password"
            type="password"
            wire:model="password"
            :label="__('Password')"
            required
            autocomplete="new-password"
            placeholder="Password"
        />

        <!-- Confirm Password -->
        <flux:input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            required
            autocomplete="new-password"
            placeholder="Confirm password"
        />

        <div class="flex items-center justify-end">
            <flux:button
                class="w-full"
                type="submit"
                variant="primary"
            >
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Already have an account?
        <flux:link
            :href="route('login')"
            wire:navigate
        >Log in</flux:link>
    </div>
</div>
