<div class="flex flex-col gap-6">
    <x-auth-header
        title="Log in to your account"
        description="Enter your email and password below to log in"
    />

    <!-- Session Status -->
    <x-auth-session-status
        class="text-center"
        :status="session('status')"
    />

    <form
        class="flex flex-col gap-6"
        wire:submit="login"
    >
        <!-- Email Address -->
        <flux:input
            name="email"
            type="email"
            wire:model="email"
            :label="__('Email address')"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                name="password"
                type="password"
                wire:model="password"
                :label="__('Password')"
                required
                autocomplete="current-password"
                placeholder="Password"
            />

            @if (Route::has('password.request'))
                <flux:link
                    class="absolute right-0 top-0 text-sm"
                    :href="route('password.request')"
                    wire:navigate
                >
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox
            wire:model="remember"
            :label="__('Remember me')"
        />

        <div class="flex items-center justify-end">
            <flux:button
                class="w-full"
                type="submit"
                variant="primary"
            >{{ __('Log in') }}</flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
            Don't have an account?
            <flux:link
                :href="route('register')"
                wire:navigate
            >Sign up</flux:link>
        </div>
    @endif
</div>
