@php declare(strict_types=1);

@endphp

<x-slot name="rightNavbar">
    <x-light-button variant="negative"
              :href='url()->previous() == url()->current() ? "/languages/$language->uuid/dictionary" : url()->previous("/languages/$language->uuid/dictionary")' wire:navigate
    >Отмена</x-light-button>
</x-slot>

<div x-data="{ selectedOrder: 0, selectedSuborder: 0,
          lexemes: $wire.entangle('lexemes'),
            create() {
                let newGroup = this.lexemes[this.lexemes.length - 1][this.lexemes[this.lexemes.length - 1].length - 1].group;
                let newLexeme = { short: '', full: '', group: newGroup, pos_uuid: null, tags: [], gramset: [], gramset_variable: [] };

                this.lexemes.push([newLexeme]);
                this.selectedSuborder = 0;
            },
            createSub() {
                let newGroup = this.lexemes[this.selectedOrder][this.lexemes[this.selectedOrder].length - 1].group;
                let newLexeme = { short: '', full: '', group: newGroup, pos_uuid: null, tags: [], gramset: [], gramset_variable: [] };

                this.lexemes[this.selectedOrder].push(newLexeme);
            },
            incrementOrder() {
                if (this.selectedOrder == this.lexemes.length - 1) {
                    this.create();
                }
            },
            incrementSuborder() {
                if (this.selectedSuborder == this.lexemes[this.selectedOrder].length - 1) {
                    this.createSub();
                }
            },
            decrementOrder() {
                if (this.selectedOrder == this.lexemes.length - 1) {
                    let ord = this.lexemes[this.selectedOrder];
                    let isDelete = true;
                    ord.forEach(function (value) {
                        if (value.short != '' || value.full != '') {
                            isDelete = false;
                        }
                    })
                    if (isDelete) {
                        this.lexemes.pop();
                    }
                }
                this.selectedSuborder = this.lexemes[this.selectedOrder].length - 1;
            },
            decrementSuborder() {
                if (this.selectedSuborder == this.lexemes[this.selectedOrder].length - 1) {
                    if (this.lexemes[this.selectedOrder][this.selectedSuborder].short == '' &&
                        this.lexemes[this.selectedOrder][this.selectedSuborder].full == '') {
                        this.lexemes[this.selectedOrder].pop();
                    }
                }
            },
          }"
>
    <form wire:submit="createArticle">
        <div class="flex flex-col gap-y-4 gap-x-4 max-w-xl mx-auto">
            <div class="grid grid-cols-2 gap-y-2 gap-x-4">
                <div>
                    <flux:input icon="language" label="Написание" wire:model="vocabula" />
                </div>
                <div>
                    <flux:input icon="speaker-wave" label="Произношение" wire:model="transcription">
                        <x-slot name="iconTrailing">
                            <flux:button size="sm" variant="subtle" icon="arrow-path" class="-mr-1" x-on:click="x2i_input('transcription')" />
                        </x-slot>
                    </flux:input>
                </div>
            </div>

            <flux:input icon="pencil" label="Адаптация" wire:model="adaptation" />

            {{-- <flux:editor label="Основная словарная статья" wire:model="full" --}}
            {{--      toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i" --}}
            {{-- /> --}}

            <div class="col-span-2">
                <flux:switch label="Публично доступно" wire:model="public" align="left" />
            </div>

            <div class="flex flex-col gap-y-4 w-full">
                <div class="rounded-xl bg-zinc-800/50 -mx-4 px-4 py-4 flex flex-col gap-2">
                    <flux:label class="text-xl">
                        <span>Лексема <span class="font-serif" x-text="romanize(lexemes[selectedOrder][selectedSuborder].group)"></span> <span class="font-serif" x-text="(selectedOrder + 1) + '.' + (selectedSuborder + 1)"></span></span>
                    </flux:label>

                    <div class="flex flex-row justify-between gap-4">
                        <div></div>

                        <div class="flex flex-row gap-2">
                            <x-order-input x-model.number="lexemes[selectedOrder][selectedSuborder].group" romanize />
                            <x-order-input x-bind:class="{ 'newable': selectedOrder == lexemes.length - 1 }"
                                    x-model.number="selectedOrder"
                                    x-on:increment="incrementOrder"
                                    x-on:decrement="decrementOrder"
                                    incrementShow />
                            <x-order-input x-bind:class="{ 'newable': selectedSuborder == lexemes[selectedOrder].length - 1 }"
                                           x-model.number="selectedSuborder"
                                           x-on:increment="incrementSuborder"
                                           x-on:decrement="decrementSuborder"
                                           incrementShow />
                        </div>
                    </div>


                    <flux:editor label="Краткая статья лексемы"
                                 x-model="lexemes[selectedOrder][selectedSuborder].short"
                                 toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                                 class="**:data-[slot=content]:min-h-[3rem]!"
                    />

                    <x-tag-list-editable class="my-2" tags="lexemes[selectedOrder][selectedSuborder].tags"/>

                    <flux:select variant="listbox"
                                 x-model="lexemes[selectedOrder][selectedSuborder].pos_uuid"
                                 placeholder="Часть речи"
                                 clearable
                                 searchable
                    >
                        @foreach($language->partOfSpeeches as $pos)
                            <flux:select.option value="{{ $pos->uuid }}">
                                {{ $pos->name }} &lt;{{ $pos->code }}&gt;
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <x-light-button variant="info"
                                    x-on:click="$flux.modal('gramset-' + lexemes[selectedOrder][selectedSuborder].pos_uuid).show()"
                    >Грамматика</x-light-button>

                    <x-gram-set class="my-2" gramset="lexemes[selectedOrder][selectedSuborder].gramset_show" />

                    <flux:editor label="Полная статья лексемы"
                                 x-model="lexemes[selectedOrder][selectedSuborder].full"
                                 toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                    />
                </div>
            </div>
            <x-light-button variant="positive" class="mt-6" type="submit">Создать</x-light-button>
        </div>
    </form>

    @foreach($language->partOfSpeeches as $pos)
        <flux:modal name="gramset-{{ $pos->uuid }}" class="w-full max-w-2xl">
            <flux:heading size="lg">{{ $pos->name }} &lt;{{ $pos->code }}&gt;</flux:heading>

            <div>
                <div class="mt-2 grid auto-cols-auto auto-rows-auto gap-2">
                    @foreach($pos->categories as $cat)
                        <flux:card class="flex flex-col gap-2">
                            <flux:heading size="lg">{{ $cat->name }} &lt;{{ $cat->code }}&gt;</flux:heading>
                            @foreach($cat->values as $val)
                                <div class="flex flex-col gap-2">
                                    <flux:checkbox x-model="lexemes[selectedOrder][selectedSuborder].gramset"
                                                   value="{{ $val->uuid }}"
                                                   label="{{ $val->name }} &lt;{{ $val->code }}&gt;"
                                    />
                                    <div x-show="lexemes[selectedOrder][selectedSuborder].gramset.includes('{{ $val->uuid }}')">
                                        <flux:switch x-model="lexemes[selectedOrder][selectedSuborder].gramset_variable"
                                                     value="{{ $val->uuid }}"
                                                     label="Изменяемое?"
                                                     align="left"
                                                     class="ms-4"
                                        />
                                    </div>
                                </div>
                            @endforeach
                        </flux:card>
                    @endforeach
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
