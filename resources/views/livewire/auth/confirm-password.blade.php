<div class="flex flex-col gap-6">
    <x-auth-header
        title="Confirm password"
        description="This is a secure area of the application. Please confirm your password before continuing."
    />

    <!-- Session Status -->
    <x-auth-session-status
        class="text-center"
        :status="session('status')"
    />

    <form
        class="flex flex-col gap-6"
        wire:submit="confirmPassword"
    >
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

        <flux:button
            class="w-full"
            type="submit"
            variant="primary"
        >{{ __('Confirm') }}</flux:button>
    </form>
</div>
