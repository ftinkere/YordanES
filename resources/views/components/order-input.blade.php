@php declare(strict_types=1) @endphp
@props([
     'romanize' => false,
     'incrementShow' => false,
])

<div class="flex flex-row items-center h-8" {{ $attributes }} x-modelable="count"
     x-data="{
        count: Number($el.getAttribute('x-model')) || 0,
        increment() { $dispatch('increment') },
        decrement() { $dispatch('decrement') },
      }"
>
    @if ($incrementShow)
        <flux:button icon="chevron-down" size="xs" class="rounded-e-none" x-on:click="decrement()" x-bind:class="{ disabled: count == 0 }" />
    @else
        <flux:button icon="chevron-down" size="xs" class="rounded-e-none" x-on:click="decrement()" x-bind:class="{ disabled: count == 1 }" />
    @endif

    @if ($romanize)
        @if ($incrementShow)
            <input class="x-input-lite row-span-2 col-span-2 w-16 text-center border-t" disabled x-bind:value="romanize(count + 1)" />
        @else
            <input class="x-input-lite row-span-2 col-span-2 w-16 text-center border-t" disabled x-bind:value="romanize(count)" />
        @endif
    @else
        @if ($incrementShow)
            <input class="x-input-lite row-span-2 col-span-2 w-8 text-center border-t" min="1" disabled x-bind:value="count + 1" />
        @else
            <input class="x-input-lite row-span-2 col-span-2 w-8 text-center border-t" min="1" disabled x-bind:value="count" />
        @endif
    @endif

    <flux:button icon="chevron-up" size="xs" x-on:click="increment()" class="newable-color rounded-s-none" />
</div>