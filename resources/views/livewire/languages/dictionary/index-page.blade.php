@php declare(strict_types=1);
    $pagination = $language->searchDictionary($search)->paginate(10);
@endphp

@if (auth()->user()?->can('update', $language))
    <x-slot name="rightNavbar">
        <x-light-button variant="positive"
                  wire:navigate href="/languages/{{ $language->uuid }}/dictionary/create"
        >Добавить статью</x-light-button>
    </x-slot>
@endif

<div class="flex flex-col gap-4">
    <flux:input icon="magnifying-glass" placeholder="ёрд" wire:model="search" wire:keydown.debounce.500ms="$refresh">
        <x-slot name="iconTrailing">
            <flux:button size="sm" variant="subtle" icon="eye" class="-mr-1" wire:click="$refresh" />
        </x-slot>
    </flux:input>


@foreach($pagination->items() as $article)
        <flux:card class="flex flex-col px-4 pt-1 mx-auto w-full dark:bg-zinc-800/40">

            <flux:link variant="subtle" :accent="false"
                       href="/dictionary/{{ $article->uuid }}" wire:navigate
            >
                <flux:heading size="lg">
                    <span class="font-bold">{{ $article->vocabula }}</span> @if($article->transcription) /<span>{{ $article->transcription }}</span>/ @endif
                    @if ($article->vocabula !== $article->adaptation)
                        <flux:subheading>{{ $article->adaptation }}</flux:subheading>
                    @endif
                </flux:heading>
            </flux:link>

            <div class="mt-4 ps-4">
                <flux:accordion class="text-sm">
                    @foreach($article->lexemes as $lexeme)
                        <flux:accordion.item transition>
                            <flux:accordion.heading>
                                <div class="flex flex-row gap-1">
                                    <div class="font-mono"><span>{{ $lexeme->order + 1}}</span>.<span>{{ $lexeme->suborder + 1 }}</span></div>
                                    <span class="break-words text-pretty">{!! $lexeme->short !!}</span>
                                </div>
                            </flux:accordion.heading>

                            @if(! empty($lexeme->full))
                                <flux:accordion.content>
                                    <div class="ps-2 break-words text-pretty">
                                        {!! $lexeme->full !!}
                                    </div>
                                </flux:accordion.content>
                            @endif
                        </flux:accordion.item>
                    @endforeach
                </flux:accordion>
            </div>
        </flux:card>
    @endforeach
    <flux:pagination :paginator="$pagination" />
</div>
