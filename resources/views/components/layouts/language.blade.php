<?php declare(strict_types=1); ?>

@props([
    'editable' => false,
    'language',
])

@php
    use App\Models\Language;

    /**
     * @var Language $language
     * @var bool $editable
     */

    $isEdit = Str::endsWith(url()->current(), '/edit');
    $editable = $editable && auth()->user()?->can('update', $language);
@endphp

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

    <div class="flex max-md:flex-col items-start gap-4 h-full">
        <div class="w-full md:w-[220px] pb-4 mr-10">
            <flux:navlist>
                <flux:navlist.item icon="information-circle" href="/languages/{{ $language->uuid }}" wire:navigate>О языке</flux:navlist.item>
                <flux:navlist.item icon="queue-list" href="/languages/{{ $language->uuid }}/dictionary" wire:navigate>Словарь</flux:navlist.item>
            </flux:navlist>
        </div>

        <div class="flex flex-col gap-2 text-black dark:text-neutral-300 w-full">
            <flux:heading size="xl" class="mx-auto text-2xl">
                {{ $language->autoname ? "{$language->autoname} /$language->autoname_transcription/ - " : '' }}
                {{ $language->name }}
            </flux:heading>

            {{ $slot }}
        </div>
    </div>
</x-layouts.app><?php
