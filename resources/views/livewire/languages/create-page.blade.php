<?php declare(strict_types=1); ?>
<div class="flex flex-col gap-4">
    <x-card>
        Название должно быть понятным без знания вашего языка другим людям.
        <br>
        Аутоним уже то как вы передаёте компьютерным способом самоназвание вашего языка.
        Можете использовать любые компьютеризированные письменности UTF.
        Но напишите произношение вашего аутонима.
    </x-card>

    <div class="grid grid-cols-2 gap-y-2 gap-x-4 max-w-xl mx-auto">
        <x-input
                class="col-span-2"
                icon="pencil"
                label="Название"
                wire:model="name"
        />
        <x-input
                icon="user"
                label="Аутоним"
                wire:model="autoname"
        />
        <x-input
                icon="speaker-wave"
                label="Произношение"
                wire:model="autoname_transcription"
        >
            <x-slot:corner>
                <x-x2i name="autoname_transcription" />
            </x-slot:corner>
        </x-input>

        <x-button class="col-span-2" wire:click="createLanguage">Создать</x-button>
    </div>
</div>
