<div class="flex flex-col gap-6">
    <x-auth-header
        title="Reset password"
        description="Please enter your new password below"
    />

    <!-- Session Status -->
    <x-auth-session-status
        class="text-center"
        :status="session('status')"
    />

    <form
        class="flex flex-col gap-6"
        wire:submit="resetPassword"
    >
        <!-- Email Address -->
        <flux:input
            id="email"
            name="email"
            type="email"
            wire:model="email"
            :label="__('Email')"
            required
            autocomplete="email"
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
                {{ __('Reset password') }}
            </flux:button>
        </div>
    </form>
</div>
