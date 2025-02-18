@php declare(strict_types=1);
    use App\Models\Language;
@endphp

<x-slot name="rightNavbar">
    <x-button light positive
        href="/languages/create" wire:navigate
    >Создать язык</x-button>
</x-slot>

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
