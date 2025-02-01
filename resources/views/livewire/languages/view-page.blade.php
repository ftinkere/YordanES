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
                <x-user-avatar :user="$language->author" height="2rem" />
            </div>
        </div>
    </div>

    <article class="mx-auto prose prose-neutral dark:prose-invert">
        {{-- TODO: Purify --}}
        {!! $language->description('about') !!}
    </article>
</div>
<?php 
