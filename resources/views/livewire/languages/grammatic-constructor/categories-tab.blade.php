<div class="flex flex-col gap-4">
    <flux:select wire:model.live="posUuid" variant="listbox" searchable clearable placeholder="Выберите Часть речи">
        @foreach($language->partOfSpeeches as $posList)
            <flux:select.option :value="$posList->uuid">
                <span>{{ $posList->name }} &lt;{{ $posList->code }}&gt;</span>
            </flux:select.option>
        @endforeach
    </flux:select>

    @php $pos = $partOfSpeech @endphp

    @if ($pos)
        <flux:modal.trigger name="add-category">
            <x-light-button>
                <flux:icon.plus variant="mini" />
                Добавить категорию
            </x-light-button>
        </flux:modal.trigger>

        <div class="sortable flex flex-col gap-4"
             x-data="{
                initSortable() {
                    const sortable = new Sortable(document.querySelectorAll('.sortable'), {
                        'draggable': '.sortable-draggable',
                        'handle': '.sortable-handle',
                    })
                    sortable.on('sortable:sorted', (event) => {
                        $wire.reorderCategories(event.data.dragEvent.source.dataset.uuid, event.oldIndex, event.newIndex);
                    })
                }
            }"
            x-init="initSortable()"
        >
            @foreach($pos->categories as $category)
                <flux:card class="sortable-draggable" data-uuid="{{ $category->uuid }}" wire:key="{{ $category->uuid }}">
                    <div class="flex flex-row gap-2 items-stretch">
                        <div class="sortable-handle w-8 self-stretch bg-zinc-500/80 dark:bg-zinc-700/80 rounded-sm cursor-grab">
                        </div>
                        <div class="grow">
                            <flux:heading>
                                <div class="flex flex-row items-center justify-between">
                                    <span>{{ $category->name }} &lt;{{ $category->code }}&gt;</span>
                                    <x-light-button variant="negative" size="sm" wire:click="deleteCategory('{{ $category->uuid }}')" wire:confirm="Вы уверены?">
                                        <flux:icon.trash variant="micro" />
                                    </x-light-button>
                                </div>
                            </flux:heading>
                            <div class="text-sm">
                                {!! $category->description ?? '' !!}
                            </div>
                        </div>
                    </div>
                </flux:card>
            @endforeach
        </div>

        <flux:modal name="add-category" class="w-full max-w-2xl">
            <div class="space-y-6">
                <flux:heading size="lg">
                    <span>Добавить категорию</span>
                </flux:heading>

                <flux:input label="Название" wire:model="categoryName" />
                <flux:input label="Код" wire:model="categoryCode" />

                <flux:editor wire:model="categoryDescription"
                             label="Описание категории"
                             toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                             class="**:data-[slot=content]:min-h-[3rem]!"
                />

                <x-light-button variant="positive" wire:click="categoryAdd">Добавить</x-light-button>
            </div>
        </flux:modal>
    @endif
</div>
