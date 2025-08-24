<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout
        :heading="__('Profile')"
        :subheading="__('Update your name and email address')"
    >
        <form
            class="my-6 w-full space-y-6"
            wire:submit="updateProfileInformation"
        >
            <flux:input
                type="text"
                wire:model="name"
                :label="__('Name')"
                required
                autofocus
                autocomplete="name"
            />

            <div>
                <flux:input
                    type="email"
                    wire:model="email"
                    :label="__('Email')"
                    required
                    disabled
                    autocomplete="email"
                />
            </div>

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
                    on="profile-updated"
                >
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
