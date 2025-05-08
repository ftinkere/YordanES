<div>
    <div class="flex flex-col gap-4">
        <flux:select wire:model.live="categoryUuid" variant="listbox" searchable clearable placeholder="Выберите Часть речи">
            <div class="flex flex-col">
                @foreach($language->partOfSpeeches as $posList)
                    <span>{{ $posList->name }} &lt;{{ $posList->code }}&gt;</span>
                    @foreach($posList->categories as $categoryList)
                        <flux:select.option value="{{ $categoryList->uuid }}">
                            <span>{{ $categoryList->name }} &lt;{{ $categoryList->code }}&gt;</span>
                        </flux:select.option>
                    @endforeach
                @endforeach
            </div>
        </flux:select>

        @php $cat = $category @endphp
        @if ($cat)
            <flux:modal.trigger name="add-value">
                <x-light-button>
                    <flux:icon.plus variant="mini" />
                    Добавить значение
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
                        $wire.reorderValues(event.data.dragEvent.source.dataset.uuid, event.oldIndex, event.newIndex);
                    })
                }
            }"
                x-init="initSortable()"
            >
                @foreach($cat->values as $value)
                    <flux:card class="sortable-draggable" data-uuid="{{ $value->uuid }}" wire:key="{{ $value->uuid }}">
                        <div class="flex flex-row gap-2 items-stretch">
                            <div class="sortable-handle w-8 self-stretch bg-zinc-500/80 dark:bg-zinc-700/80 rounded-sm cursor-grab">
                            </div>
                            <div class="grow">
                                <flux:heading>
                                    <div class="flex flex-row items-center justify-between">
                                        <span>{{ $value->name }} &lt;{{ $value->code }}&gt;</span>
                                        <x-light-button variant="negative" size="sm" wire:click="deleteCategory('{{ $value->uuid }}')" wire:confirm="Вы уверены?">
                                            <flux:icon.trash variant="micro" />
                                        </x-light-button>
                                    </div>
                                </flux:heading>
                                <div class="text-sm">
                                    {!! $value->description ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        @endif
    </div>

    <flux:modal name="add-value" class="w-full max-w-2xl">
        <div class="space-y-6">
            <flux:heading size="lg">
                <span>Добавить категорию</span>
            </flux:heading>

            <flux:input label="Название" wire:model="valueName" />
            <flux:input label="Код" wire:model="valueCode" />

            <flux:editor wire:model="valueDescription"
                         label="Описание Значения"
                         toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                         class="**:data-[slot=content]:min-h-[3rem]!"
            />

            <x-light-button variant="positive" wire:click="addValue">Добавить</x-light-button>
        </div>
    </flux:modal>
</div>
