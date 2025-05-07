@php use App\Models\GrammaticPartOfSpeech; @endphp
<div class="flex flex-col gap-4">
    <flux:modal.trigger name="add-pos">
        <x-light-button color="positive">
            <flux:icon.plus variant="mini" />
            Добавить Часть речи
        </x-light-button>
    </flux:modal.trigger>

    <div class="sortable flex flex-col gap-4"
        x-data="{
            initSortable() {
                const sortable = new Sortable(document.querySelectorAll('.sortable'), {
                    'draggable': '.sortable-draggable',
                    'handle': '.sortable-handle'
                })
                sortable.on('sortable:sorted', (event) => {
                    $wire.reorderPartOfSpeech(event.data.dragEvent.source.dataset.uuid, event.oldIndex, event.newIndex);
                })
            }
        }"
        x-init="initSortable()"
    >
        @foreach($language->partOfSpeeches as $pos)
            <flux:card class="sortable-draggable" data-uuid="{{ $pos->uuid }}" wire:key="{{ $pos->uuid }}">
                <div class="flex flex-row gap-2 items-stretch">
                    <div class="sortable-handle w-8 self-stretch bg-zinc-500/80 dark:bg-zinc-700/80 rounded-sm cursor-grab">
                    </div>
                    <div class="grow">
                        <flux:heading>
                            <div class="flex flex-row items-center justify-between">
                                <span>{{ $pos->name }} &lt;{{ $pos->code }}&gt;</span>
                                <x-light-button variant="negative" size="sm" wire:click="deletePartOfSpeech('{{ $pos->uuid }}')" wire:confirm="Вы уверены?">
                                    <flux:icon.trash variant="micro" />
                                </x-light-button>
                            </div>
                        </flux:heading>
                        <div class="text-sm">
                            {!! $pos->description ?? '' !!}
                        </div>
                    </div>
                </div>
            </flux:card>
        @endforeach
    </div>

    <flux:modal name="add-pos" class="w-2xl">
        <div class="space-y-6">
            <flux:heading size="lg">
                <span>Добавить Часть речи</span>
                <flux:modal.trigger name="add-pos-template">
                    <x-light-button variant="info">
                        Выбрать шаблон
                    </x-light-button>
                </flux:modal.trigger>
            </flux:heading>

            <flux:input label="Название" wire:model="posName" />
            <flux:input label="Код" wire:model="posCode" />

            <flux:editor wire:model="posDescription"
                    label="Описание части речи"
                    toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                    class="**:data-[slot=content]:min-h-[3rem]!"
            />

            <x-light-button variant="positive" wire:click="addPartOfSpeech">Добавить</x-light-button>
        </div>
    </flux:modal>

    <flux:modal name="add-pos-template" class="w-2xl">
        <div class="space-y-6">
            <flux:heading size="lg">Выбрать шаблон</flux:heading>
            <div class="flex flex-col gap-2">
                @foreach(GrammaticPartOfSpeech::defaults()->get() as $pos)
                    <flux:card>
                        <flux:heading>
                            <div class="flex flex-row items-center justify-between">
                                <span>{{ $pos->name }} &lt;{{ $pos->code }}&gt;</span>
                                <x-light-button variant="info" wire:click="setPartOfSpeechTemplate('{{ $pos->uuid }}')">
                                    Выбрать
                                </x-light-button>
                            </div>
                        </flux:heading>
                        <div class="text-sm">
                            {!! $pos->description ?? '' !!}
                        </div>
                    </flux:card>
                @endforeach

            </div>
        </div>
    </flux:modal>
</div>
