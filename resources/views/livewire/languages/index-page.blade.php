@php declare(strict_types=1);
    use App\Models\Language;
@endphp

<x-slot name="rightNavbar">
    <x-light-button variant="positive"
        href="/languages/create" wire:navigate
    >Создать язык</x-light-button>
</x-slot>

<div class="grid grid-cols-1 px-32 gap-2 items-start">
    @foreach($languages as $language)
    @php /** @var Language $language */ @endphp
    <flux:card class="cursor-pointer" href="/languages/{{ $language->uuid }}" wire:navigate>
        <flux:heading class="mx-auto w-fit" size="lg">
            {{ $language->autoname }} /{{ $language->autoname_transcription }}/
            <flux:subheading>{{ $language->name }}</flux:subheading>
        </flux:heading>
    </flux:card>
    @endforeach
</div>
