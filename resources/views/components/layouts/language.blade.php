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
        <flux:navlist class="sticky top-4 z-10">
            <flux:navlist.item icon="information-circle"
                               href="/languages/{{ $language->uuid }}" wire:navigate
            >О языке</flux:navlist.item>
            <flux:navlist.item icon="queue-list"
                               href="/languages/{{ $language->uuid }}/dictionary" wire:navigate
                               :current="request()->is('*/dictionary*')"
            >Словарь</flux:navlist.item>
        </flux:navlist>
    </x-slot>

    <div class="flex max-md:flex-col items-start gap-4 h-full">
        {{-- <div class="relative w-full md:w-[220px] pb-4 mr-10"> --}}
        {{--     <div class="absolute top-[-8.5rem] р-14 -right-4 -left-36 h-screen bg-zinc-200 dark:bg-zinc-800 -z-10 drop-shadow-lg"></div> --}}

        {{--     <flux:navlist class="z-10"> --}}
        {{--         <flux:navlist.item icon="information-circle" --}}
        {{--                            href="/languages/{{ $language->uuid }}" wire:navigate --}}
        {{--         >О языке</flux:navlist.item> --}}
        {{--         <flux:navlist.item icon="queue-list" --}}
        {{--                            href="/languages/{{ $language->uuid }}/dictionary" wire:navigate --}}
        {{--                            :current="request()->is('*/dictionary*')" --}}
        {{--         >Словарь</flux:navlist.item> --}}
        {{--     </flux:navlist> --}}
        {{-- </div> --}}

        <div class="flex flex-col gap-2 text-black dark:text-neutral-300 w-full">
            <flux:heading size="xl" class="mx-auto text-2xl">
                {{ $language->autoname ? "{$language->autoname} /$language->autoname_transcription/ - " : '' }}
                {{ $language->name }}
            </flux:heading>

            {{ $slot }}
        </div>
    </div>
</x-layouts.app><?php
