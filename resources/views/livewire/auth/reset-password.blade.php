<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Reset password')"
        :description="__('Please enter your new password below')"
    />

    <!-- Session Status -->
    <x-auth-session-status
        class="text-center"
        :status="session('status')"
    />

    <form
        class="flex flex-col gap-6"
        method="POST"
        wire:submit="resetPassword"
    >
        <!-- Email Address -->
        <flux:input
            type="email"
            wire:model="email"
            :label="__('Email')"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <flux:input
            type="password"
            wire:model="password"
            :label="__('Password')"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            type="password"
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
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
