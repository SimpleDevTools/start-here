<div class="space-y-8">
    @if (app()->environment('local'))
        @foreach ($this->localLoginEmails() as $loginEmail)
            <x-filament::button
                class="w-full"
                wire:click="localLoginAs('{{ $loginEmail }}')"
                outlined
            >
                {{ __('filament::login.buttons.submit.label') }} as {{ $loginEmail }}
            </x-filament::button>
        @endforeach
    @endif

    <form
        class="space-y-8"
        wire:submit.prevent="authenticate"
    >
        {{ $this->form }}

        <x-filament::button
            class="w-full"
            form="authenticate"
            type="submit"
        >
            {{ __('filament::login.buttons.submit.label') }}
        </x-filament::button>
    </form>
</div>
