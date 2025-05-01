@php declare(strict_types=1);

 @endphp

@props(['tags' => 'tags'])

<div {{ $attributes->class('flex flex-row') }}>
    <template x-for="{ name, color } in {{ $tags }}" x-bind:key="name">
        <span class="tag"
              x-text="name"
              x-bind:data-color="color"
        ></span>
    </template>
</div>