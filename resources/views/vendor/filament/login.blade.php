<div class="space-y-8">
    @env('local')
        @foreach ($this->localLoginEmails() as $loginEmail)
            <x-filament::button wire:click="localLoginAs('{{ $loginEmail }}')" class="w-full" outlined>
                {{ __('filament::login.buttons.submit.label') }} as {{ $loginEmail }}
            </x-filament::button>
        @endforeach
    @endenv

    <form wire:submit.prevent="authenticate" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit" form="authenticate" class="w-full">
            {{ __('filament::login.buttons.submit.label') }}
        </x-filament::button>
    </form>
</div>
