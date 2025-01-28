<div>
    <div class="mb-6 grid grid-cols-2 gap-x-2 gap-y-4 max-w-xl mx-auto">
        <x-input label="Название" wire:model="name" class="col-span-2" />
        <x-input label="Аутоним" wire:model="autoname" />
        <x-input label="Произношение" wire:model="autoname_transcription">
            <x-slot:corner>
                <x-x2i name="autoname_transcription" />
            </x-slot:corner>
        </x-input>

        <x-button positive label="Сохранить" class="col-span-2" wire:click="updateLanguage" />
    </div>

    <div class="mx-auto max-w-xl">
        <x-textarea label="Описание о языке" rows="10" wire:model="about" />
    </div>
</div>
