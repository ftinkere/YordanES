<?php

declare(strict_types=1);

?>
@php
    use App\Models\Language;

    /**
     * @var Language $language
     * @var bool $editable
     */

    $isEdit = Str::endsWith(url()->current(), '/edit');
    $editable = $editable && auth()->user()?->can('update', $language);
@endphp

@props([
    'editable' => false,
    'language',
])

<x-layouts.app>
    @if ($editable)
        <x-slot:rightNavbar>
            @if($isEdit)
                <x-button
                    negative light
                    wire:navigate
                    href="{{ Str::replaceLast('/edit', '', url()->current()) }}"
                >
                    Отменить
                </x-button>
            @else
                <x-button
                    primary light
                    wire:navigate
                    href="{{ url()->current() . '/edit' }}"
                >
                    Изменить
                </x-button>
            @endif
        </x-slot:rightNavbar>
    @endif

    <div>
        <x-link underline href="/languages/{{ $language->uuid }}/" wire:navigate>О языке</x-link>
        <x-link underline href="/languages/{{ $language->uuid }}/dictionary" wire:navigate>Словарь</x-link>
    </div>

    <div class="flex flex-col gap-2 text-black dark:text-neutral-300">
        <h1 class="mx-auto text-2xl">
            {{ $language->autoname ? "{$language->autoname} /$language->autoname_transcription/ - " : '' }}
            {{ $language->name }}
        </h1>

        {{ $slot }}
    </div>
</x-layouts.app><?php 
