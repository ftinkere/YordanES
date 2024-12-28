@php use App\Models\Language; @endphp

<div class="flex flex-col gap-2">
    <div class="w-fit">
        <x-button wire:navigate href="/languages/create">Создать язык</x-button>
    </div>

    @foreach($languages as $language)
    @php /** @var Language $language */ @endphp
        <div wire:key="{{ $language->uuid }}" class="dark:text-white">
            {{ $language->name }}
        </div>
    @endforeach
</div>
