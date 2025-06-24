@php declare(strict_types=1);
    use App\Models\Language;
@endphp

<x-slot name="rightNavbar">
    <x-light-button variant="positive"
        href="/languages/create" wire:navigate
    >Создать язык</x-light-button>
</x-slot>

<div class="flex flex-col max-w-4xl w-full gap-2 items-start">

    <div>
        <flux:switch wire:model.live="my" label="Только свои" align="left" />
    </div>

    @foreach($languages as $language)
        @php /** @var Language $language */ @endphp
        <flux:card class="cursor-pointer w-full" href="/languages/{{ $language->uuid }}" wire:key="{{ $language->uuid }}" wire:navigate>
            <flux:heading class="mx-auto w-fit" size="lg">
                {{ $language->autoname }} /{{ $language->autoname_transcription }}/
                <flux:subheading>
                    {{ $language->name }}
                    @unless($language->is_published)
                        <flux:tooltip content="Не опубликовано">
                            <flux:icon.eye variant="micro" class="inline text-negative-700 dark:text-negative-500" />
                        </flux:tooltip>
                    @endunless
                </flux:subheading>
            </flux:heading>
        </flux:card>
    @endforeach
</div>
