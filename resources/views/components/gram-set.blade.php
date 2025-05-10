@php declare(strict_types=1);

@endphp

@props([
    'gramset' => 'gramset',
])

<div x-data="{
    gramset: {{ $gramset }},
    get gramset_var() { return this.gramset.filter(gram => gram.is_changeable) },
    get gramset_const() { return this.gramset.filter(gram => !gram.is_changeable) },
}"
class="flex flex-row gap-2"
>
    <span x-show="gramset_const.length > 0">
        (
        <template x-for="gram in gramset_const" x-bind:key="gram.value.code">
            <span class="px-2" x-text="gram.value.code"></span>
        </template>
        )
    </span>
    <span x-show="gramset_var.length > 0">
        [
        <template x-for="gram in gramset_var" x-bind:key="gram.value.code">
            <span class="px-2" x-text="gram.value.code"></span>
        </template>
        ]
    </span>
</div>