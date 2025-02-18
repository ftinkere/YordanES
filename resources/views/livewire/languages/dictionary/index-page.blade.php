@php declare(strict_types=1) @endphp

<x-slot name="rightNavbar">
    <x-button light positive
              wire:navigate href="/languages/{{ $language->uuid }}/dictionary/create"
    >Добавить статью</x-button>
</x-slot>

<div class="flex flex-col gap-4">
    @foreach($language->dictionary as $article)
        {{ $article->vocabula->vocabula }}
    @endforeach
</div>
