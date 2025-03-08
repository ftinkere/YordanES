@php declare(strict_types=1);
    use App\Helpers\CommonHelper;
@endphp

@if (auth()->user()?->can('update', $language))
    <x-slot name="rightNavbar">
         <x-light-button href="{{ url()->current() . '/edit' }}" wire:navigate>Изменить</x-light-button>
        {{-- <flux:badge color="teal" as="button" class="cursor-pointer px-4! py-2!">Изменить</flux:badge> --}}
    </x-slot>
@endif

<div>
    {{-- Row --}}
    <div class="flex flex-row justify-between">
        {{-- Left --}}
        <div>

        </div>

        {{-- Right --}}
        <div class="flex gap-4 items-center">
            <span class="text-sm">С нами с {{ CommonHelper::formatDate($language->created_at) }}</span>

            <div class="ps-3 flex flex-row gap-2 items-center rounded-full dark:hover:bg-zinc-700 cursor-pointer">
                <span>{{ $language->author->name }}</span>
                <x-avatar avatar="{{  $language->author->avatar }}" size="2rem" name="{{ $language->author->name }}" />
            </div>
        </div>
    </div>

    <article class="mx-auto prose prose-zinc dark:prose-invert">
        {{-- TODO: Purify --}}
        {!! $language->description('about')?->description ?? '' !!}
    </article>
</div>
