<?php declare(strict_types=1); ?>

@props([
    'editable' => false,
    'language',
    'rightNavbar' => null,
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
    @if ($rightNavbar)
        <x-slot:rightNavbar>
            {{ $rightNavbar }}
        </x-slot:rightNavbar>
    @endif

    <x-slot name="sidebar">
        <flux:navlist class="md:sticky top-4 z-10 mt-4 p-2">
            <flux:navlist.item icon="information-circle"
                               href="/languages/{{ $language->uuid }}" wire:navigate
                               :current="request()->routeIs('languages.view', 'languages.update')"
            >О языке</flux:navlist.item>
            <flux:navlist.item icon="queue-list"
                               href="/languages/{{ $language->uuid }}/dictionary" wire:navigate
                               :current="request()->routeIs('languages.dictionary.*')"
            >Словарь</flux:navlist.item>
        </flux:navlist>
    </x-slot>

    <div class="flex flex-row max-md:flex-col items-start gap-4 h-full">
        <div class="flex flex-col gap-2 text-black dark:text-zinc-300 w-full">
            <flux:heading size="xl" class="mx-auto text-2xl drop-shadow-xl">
                {{ $language->autoname ? "{$language->autoname} /$language->autoname_transcription/ - " : '' }}
                {{ $language->name }}
            </flux:heading>

            <div class="container max-w-5xl mx-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.app><?php
