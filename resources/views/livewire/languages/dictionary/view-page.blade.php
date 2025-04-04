@php declare(strict_types=1) @endphp

@if (auth()->user()?->can('update', $article))
    <x-slot name="rightNavbar">
        <x-light-button variant="primary" wire:navigate href="/dictionary/{{ $article->uuid }}/edit"
        >Изменить</x-light-button>
    </x-slot>
@endif

<div class="grid grid-cols-1 gap-y-4">
    <div>
        <p class="text-2xl text-zinc-100"><span class="font-bold">{{ $article->vocabula }}</span> @if($article->transcription) /<span>{{ $article->transcription }}</span>/ @endif </p>
        @unless($article->vocabula === $article->adaptation)
        <p class="text-xl">{{ $article->adaptation }}</p>
        @endunless
    </div>

    @if($article->article)
    <div class="break-words text-pretty">
        {!! $article->article !!}
    </div>
    @endif

    @if($article->lexemes)
    <div class="grid grid-cols-1 gap-y-1">
        @foreach($article->lexemes as $lexeme)
        <div class="pl-4 flex flex-row gap-2">
            <div class="font-mono"><span>{{ $lexeme->order + 1}}</span>.<span>{{ $lexeme->suborder + 1 }}</span></div>
            <span class="break-words text-pretty">{!! $lexeme->short !!}</span>
        </div>
        @endforeach
    </div>
    <div class="grid grid-cols-[auto_minmax(0,100%)] mt-4 gap-x-4 gap-y-1">
        @foreach($article->lexemesGrouped() as $group => $lexemes)
        <h5 class="mt-4 font-bold font-serif justify-self-center" x-text="romanize({{ $group }})"></h5>
        <flux:separator />

        @foreach($lexemes as $lexeme)
        <div class="row-span-2 font-mono"><span>{{ $lexeme->order + 1}}</span>.<span>{{ $lexeme->suborder + 1 }}</span></div>

        <span class="break-words text-pretty">{!! $lexeme->short !!}</span>
        <div class="break-words text-pretty">
            {!! $lexeme->full !!}
        </div>
        @endforeach

        @endforeach
    </div>
    @endif

    <div class="h-64"></div>
</div>