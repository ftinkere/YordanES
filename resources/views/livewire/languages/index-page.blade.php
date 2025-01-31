<?php

declare(strict_types=1);

?>
@php use App\Models\Language; @endphp

<div class="flex flex-col gap-2">
    <div class="w-fit">
        <x-button wire:navigate href="/languages/create">Создать язык</x-button>
    </div>

    <div class="flex flex-col gap-2 items-start">
        @foreach($languages as $language)
        @php /** @var Language $language */ @endphp
            <x-link
                wire:key="{{ $language->uuid }}"
                class="dark:text-white"
                wire:navigate
                href="/languages/{{ $language->uuid }}"
            >
                {{ $language->name }}
            </x-link>
        @endforeach
    </div>
</div>
<?php 
