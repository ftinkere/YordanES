@php declare(strict_types=1);

@endphp

@props(['tags' => 'tags'])

<div {{ $attributes->class('flex flex-row gap-1 items-center') }}
    x-data="{
        deleteTag(uuid) {
            let arr = {{ $tags }};
            let idx = arr.findIndex(tag => tag.uuid === uuid);
            if (idx > -1) {
                arr.splice(idx, 1);
            }
        },
        createTag(name) {
            let arr = {{ $tags }};
            let newTag = { name: name, color: 'amber' };
            arr.push(newTag);
        }
    }"
>
    <template x-for="{ uuid, name, color } in {{ $tags }}" x-bind:key="name">
        <span class="tag" x-bind:data-color="color">
            <span x-text="name"></span>
            <flux:icon.x-mark variant="micro" class="ms-1 text-zinc-950 dark:text-zinc-200 rounded-full bg-secondary-800/50 hover:bg-negative-800/90 dark:bg-secondary-400/50 hover:dark:bg-negative-400/90 cursor-pointer"
                x-on:click="deleteTag(uuid)"
            />
        </span>
    </template>
    <flux:icon.plus variant="mini" class="ms-2 text-zinc-950 dark:text-zinc-200 rounded-full bg-positive-300 hover:bg-positive-400 dark:bg-positive-800 hover:dark:bg-positive-700 cursor-pointer"
        x-on:click="createTag(prompt('Введите название тега'))"
    />
</div>