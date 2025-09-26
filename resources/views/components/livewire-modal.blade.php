@props(['name'])
<flux:modal
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'md:w-3xl max-w-3xl']) }}
    x-init="$nextTick(() => Flux.modal('{{ $name }}').show())"
    x-on:close="$wire.dispatch('modal.close', '{{ $name }}')"
>
    {{ $slot }}
</flux:modal>
