<?php

declare(strict_types=1);

?>
@php
    use App\Helpers\CommonHelper;
@endphp
<div>
    {{-- Row --}}
    <div class="flex flex-row justify-between">
        {{-- Left --}}
        <div>

        </div>

        {{-- Right --}}
        <div class="flex gap-4 items-center">
            <span class="text-sm">С нами с {{ CommonHelper::formatDate($language->created_at) }}</span>

            <div class="ps-3 flex flex-row gap-2 items-center rounded-full hover:dark:bg-neutral-700 cursor-pointer">
                <span>{{ $language->author->name }}</span>
                <x-avatar :label="$language->author->avatar ? null : mb_substr($language->author->name ?? 'А', 0, 1)"
                          :src="$language->author->avatar"
                          xs
                />
            </div>
        </div>
    </div>

    <article class="mx-auto prose prose-neutral dark:prose-invert">
        {{-- TODO: Purify --}}
        {!! Str::of($language->description('about'))->markdown() !!}
    </article>
</div>
<?php 
