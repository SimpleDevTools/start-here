<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status
            class="text-center"
            :status="session('status')"
        />

        <form
            class="flex flex-col gap-6"
            method="POST"
            action="{{ route('password.confirm.store') }}"
        >
            @csrf

            <flux:input
                name="password"
                type="password"
                :label="__('Password')"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <flux:button
                class="w-full"
                data-test="confirm-password-button"
                type="submit"
                variant="primary"
            >
                {{ __('Confirm') }}
            </flux:button>
        </form>
    </div>
</x-layouts.auth>
