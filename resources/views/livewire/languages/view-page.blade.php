@php declare(strict_types=1);
    use App\Helpers\CommonHelper;
@endphp

@can('update', $language)
    <x-slot name="rightNavbar">
         {{-- <x-light-button href="{{ url()->current() . '/edit' }}" wire:navigate>Изменить</x-light-button> --}}
         <flux:badge color="teal" as="button" class="cursor-pointer px-4! py-2!"
                     href="{{ url()->current() . '/edit' }}" wire:navigate
         >Изменить</flux:badge>
    </x-slot>
@endcan

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
                <flux:avatar :src="$language->author->avatar" :name="$language->author->name" size="sm" circle />
            </div>
        </div>
    </div>

    <article class="mx-auto prose prose-zinc dark:prose-invert">
        {!! $language->description('about')?->description ?? '' !!}
    </article>
</div>
