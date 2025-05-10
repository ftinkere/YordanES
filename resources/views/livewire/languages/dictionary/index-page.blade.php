@php declare(strict_types=1);
    use App\Models\DictionaryArticle;

    $pagination = $language->searchDictionary($search, Auth::user())->paginate(10);
@endphp

@can('update', $language)
    <x-slot name="rightNavbar">
        <x-light-button variant="positive"
                        wire:navigate href="/languages/{{ $language->uuid }}/dictionary/create"
        >Добавить статью
        </x-light-button>
    </x-slot>
@endif

<div class="flex flex-col gap-4">
    <flux:input icon="magnifying-glass" placeholder="ёрд" wire:model="search" wire:keydown.debounce.500ms="$refresh">
        <x-slot name="iconTrailing">
            <flux:button size="sm" variant="subtle" icon="eye" class="-mr-1" wire:click="$refresh"/>
        </x-slot>
    </flux:input>


    @foreach($pagination->items() as $article)
        @php /** @var DictionaryArticle $article */ @endphp
        <flux:card class="flex flex-col px-4 pt-1 mx-auto w-full dark:bg-zinc-800/40">

            <div class="flex flex-row gap-4">
                <flux:link variant="subtle" :accent="false"
                           href="/dictionary/{{ $article->uuid }}" wire:navigate
                >
                    <flux:heading size="lg">
                        <span class="font-bold">{{ $article->vocabula }}</span>
                        @if($article->transcription)
                            /<span>{{ $article->transcription }}</span>/
                        @endif
                        @unless($article->is_published)
                            <flux:tooltip content="Не опубликовано">
                                <flux:icon.eye variant="micro" class="inline text-negative-700 dark:text-negative-500" />
                            </flux:tooltip>
                        @endunless
                        @if ($article->vocabula !== $article->adaptation)
                            <flux:subheading>{{ $article->adaptation }}</flux:subheading>
                        @endif
                    </flux:heading>
                </flux:link>
                @if($article->files->isNotEmpty())
                    @php $file = $article->files[0] @endphp
                    <div id="images" class="pswp-gallery" x-init="imagesInit()"
                         x-data="{
                            imagesInit: function () {
                                const lightbox = new PhotoSwipeLightbox({
                                    gallery: '#images',
                                    children: 'a',
                                    pswpModule: PhotoSwipe,
                                })
                                lightbox.init()
                            }
                        }"
                    >
                        <a data-pswp-src="{{ $file->path }}"
                           data-pswp-height="{{ $file->height }}"
                           data-pswp-width="{{ $file->width }}"
                        >
                            <img src="{{ $file->path }}" alt="Изображение" style="height: 3em;" class="rounded-md" />
                        </a>
                    </div>
                @endif
            </div>

            <div class="mt-4 ps-4">
                <flux:accordion class="text-sm">
                    @foreach($article->lexemes as $lexeme)
                        <flux:accordion.item transition>
                            <flux:accordion.heading>
                                <div class="flex flex-row gap-1">
                                    <div class="font-mono">
                                        <span>{{ $lexeme->order + 1}}</span>.<span>{{ $lexeme->suborder + 1 }}</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-1">
                                        <span class="break-words text-pretty">{!! $lexeme->short !!}</span>
                                        <div class="flex flex-row gap-2">
                                            @if($lexeme->partOfSpeech)
                                                <span>&lt;{{ $lexeme->partOfSpeech->code }}&gt;</span>
                                            @endif
                                            @if($lexeme->gramSet->where('is_changeable', false)->count() > 0)
                                                <span>
                                                    (
                                                    @foreach($lexeme->gramSet->where('is_changeable', false) as $set)
                                                        {{ $set->value->code }}
                                                    @endforeach
                                                )
                                                </span>
                                            @endif
                                            @if($lexeme->gramSet->where('is_changeable', true)->count() > 0)
                                                <span>
                                                    [
                                                    @foreach($lexeme->gramSet->where('is_changeable', true) as $set)
                                                        {{ $set->value->code }}
                                                    @endforeach
                                                    ]
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-row gap-1">
                                            @foreach($lexeme->tags as $tag)
                                                @php /** @var Tag $tag */ @endphp
                                                <flux:badge sizae="sm" :color="$tag->color ?? 'amber'">{{ $tag->name }}</flux:badge>
                                            @endforeach
                                        </div>
                                    </div>
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
    <flux:pagination :paginator="$pagination"/>
</div>
