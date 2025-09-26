<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Log in to your account')"
        :description="__('Enter your email and password below to log in')"
    />

    <!-- Session Status -->
    <x-auth-session-status
        class="text-center"
        :status="session('status')"
    />

    <form
        class="flex flex-col gap-6"
        method="POST"
        wire:submit="login"
    >
        <!-- Email Address -->
        <flux:input
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
                type="password"
                wire:model="password"
                :label="__('Password')"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link
                    class="absolute end-0 top-0 text-sm"
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
                data-test="login-button"
                type="submit"
                variant="primary"
            >
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-center text-sm text-zinc-600 rtl:space-x-reverse dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link
                :href="route('register')"
                wire:navigate
            >{{ __('Sign up') }}</flux:link>
        </div>
    @endif
</div>
