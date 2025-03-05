<div class="mt-4 flex flex-col gap-6">
    <flux:text class="text-center">
        {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
    </flux:text>

    @if (session('status') == 'verification-link-sent')
        <flux:text class="!dark:text-green-400 text-center font-medium !text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </flux:text>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button
            class="w-full"
            wire:click="sendVerification"
            variant="primary"
        >
            {{ __('Resend verification email') }}
        </flux:button>

        <flux:link
            class="cursor-pointer text-sm"
            wire:click="logout"
        >
            {{ __('Log out') }}
        </flux:link>
    </div>
</div>
