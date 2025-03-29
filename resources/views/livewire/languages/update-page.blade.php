@php declare(strict_types=1); @endphp

<x-slot name="rightNavbar">
    <x-light-button variant="negative"
        href="/languages/{{ $language->uuid }}" wire:navigate
    >Отменить</x-light-button>
</x-slot>

<div>
    <div class="mb-6 grid grid-cols-2 gap-x-2 gap-y-4 max-w-xl mx-auto">
        <div class="col-span-2">
            <flux:input icon="pencil" label="Название" wire:model="name" />
        </div>
        <flux:input icon="user" label="Аутоним" wire:model="autoname" />
        <flux:input icon="speaker-wave" label="Произношение" wire:model="autoname_transcription">
            <x-slot name="iconTrailing">
                <flux:button size="sm" variant="subtle" icon="arrow-path" class="-mr-1" x-on:click="x2i_input('autoname_transcription')" />
            </x-slot>
        </flux:input>

        <x-light-button variant="positive" class="col-span-2" wire:click="updateLanguage">
            Сохранить
        </x-light-button>
    </div>

    <div class="mx-auto max-w-xl">
        <flux:editor
                label="Описание о языке"
                wire:model="about"
                toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
        />
    </div>
</div>
