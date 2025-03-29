@php declare(strict_types=1);

@endphp

<x-slot name="rightNavbar">
    <x-light-button variant="negative"
                    href="/dictionary/$dictionaryArticle->uuid" wire:navigate
    >Отмена</x-light-button>
</x-slot>

<div>
    <form wire:submit="updateArticle"
          x-data="{ selectedOrder: 0, selectedSuborder: 0,
            lexemes: $wire.entangle('lexemes'),
            create() {
                let newGroup = this.lexemes[this.lexemes.length - 1][this.lexemes[this.lexemes.length - 1].length - 1].group;
                let newLexeme = { short: '', full: '', group: newGroup };

                this.lexemes.push([newLexeme]);
                this.selectedSuborder = 0;
            },
            createSub() {
                let newGroup = this.lexemes[this.selectedOrder][this.lexemes[this.selectedOrder].length - 1].group;
                let newLexeme = { short: '', full: '', group: newGroup };

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
          }">
        <div class="flex flex-col gap-y-2 gap-x-4 max-w-xl mx-auto">
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

            <flux:editor label="Основная словарная статья" wire:model="full"
                         toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
            />

            <div class="flex flex-col gap-y-4 w-full">
                <div class="rounded-xl bg-zinc-800/50 -mx-4 px-4 py-4">
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
                    <br />
                    <flux:editor label="Полная статья лексемы"
                                 x-model="lexemes[selectedOrder][selectedSuborder].full"
                                 toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                    />
                </div>
            </div>

            <x-light-button variant="positive" class="mt-6" type="submit">Обновить</x-light-button>
        </div>
    </form>
</div>
