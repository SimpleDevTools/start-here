<div
    class="space-y-6 rounded-xl border border-zinc-200 py-6 shadow-sm dark:border-white/10"
    wire:cloak
    x-data="{ showRecoveryCodes: false }"
>
    <div class="space-y-2 px-6">
        <div class="flex items-center gap-2">
            <flux:icon.lock-closed
                class="size-4"
                variant="outline"
            />
            <flux:heading
                size="lg"
                level="3"
            >{{ __('2FA Recovery Codes') }}</flux:heading>
        </div>
        <flux:text variant="subtle">
            {{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}
        </flux:text>
    </div>

    <div class="px-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <flux:button
                aria-expanded="false"
                aria-controls="recovery-codes-section"
                x-show="!showRecoveryCodes"
                icon="eye"
                icon:variant="outline"
                variant="primary"
                @click="showRecoveryCodes = true;"
            >
                {{ __('View Recovery Codes') }}
            </flux:button>

            <flux:button
                aria-expanded="true"
                aria-controls="recovery-codes-section"
                x-show="showRecoveryCodes"
                icon="eye-slash"
                icon:variant="outline"
                variant="primary"
                @click="showRecoveryCodes = false"
            >
                {{ __('Hide Recovery Codes') }}
            </flux:button>

            @if (filled($recoveryCodes))
                <flux:button
                    x-show="showRecoveryCodes"
                    icon="arrow-path"
                    variant="filled"
                    wire:click="regenerateRecoveryCodes"
                >
                    {{ __('Regenerate Codes') }}
                </flux:button>
            @endif
        </div>

        <div
            class="relative overflow-hidden"
            id="recovery-codes-section"
            x-show="showRecoveryCodes"
            x-transition
            x-bind:aria-hidden="!showRecoveryCodes"
        >
            <div class="mt-3 space-y-3">
                @error('recoveryCodes')
                    <flux:callout
                        variant="danger"
                        icon="x-circle"
                        heading="{{ $message }}"
                    />
                @enderror

                @if (filled($recoveryCodes))
                    <div
                        class="grid gap-1 rounded-lg bg-zinc-100 p-4 font-mono text-sm dark:bg-white/5"
                        role="list"
                        aria-label="Recovery codes"
                    >
                        @foreach ($recoveryCodes as $code)
                            <div
                                class="select-text"
                                role="listitem"
                                wire:loading.class="opacity-50 animate-pulse"
                            >
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <flux:text
                        class="text-xs"
                        variant="subtle"
                    >
                        {{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate Codes above.') }}
                    </flux:text>
                @endif
            </div>
        </div>
    </div>
</div>
