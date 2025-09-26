<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout
        :heading="__('Update password')"
        :subheading="__('Ensure your account is using a long, random password to stay secure')"
    >
        <form
            class="mt-6 space-y-6"
            method="POST"
            wire:submit="updatePassword"
        >
            <flux:input
                type="password"
                wire:model="current_password"
                :label="__('Current password')"
                required
                autocomplete="current-password"
            />
            <flux:input
                type="password"
                wire:model="password"
                :label="__('New password')"
                required
                autocomplete="new-password"
            />
            <flux:input
                type="password"
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button
                        class="w-full"
                        type="submit"
                        variant="primary"
                    >{{ __('Save') }}</flux:button>
                </div>

                <x-action-message
                    class="me-3"
                    on="password-updated"
                >
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
