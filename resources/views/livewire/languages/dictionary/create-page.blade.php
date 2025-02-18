@php declare(strict_types=1);

@endphp

<x-slot name="rightNavbar">
    <x-button light negative
              :href='url()->previous("/languages/$language->uuid/dictionary")'
    >Отмена</x-button>
</x-slot>

<div>
    <form x-on:submit="$wire.set('lexemes', lexemes)" wire:submit="createArticle"
          x-data="{ selectedOrder: 0, selectedSuborder: 0, lexemes: [[{ short: '', full: '', group: 1}]],
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
                <flux:input icon="language" label="Написание" wire:model="vocabula" />
                <flux:input icon="speaker-wave" label="Произношение" wire:model="transcription">
                    <x-slot name="iconTrailing">
                        <flux:button size="sm" variant="subtle" icon="arrow-path" class="-mr-1" x-on:click="x2i_input('transcription')" />
                    </x-slot>
                </flux:input>
            </div>

            <flux:input icon="pencil" label="Адаптация" wire:model="adaptation" />

            <flux:editor label="Краткая статья" wire:model="short"
                 toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                 class="[&_[data-slot=content]]:min-h-[2rem]"
            />

            <flux:editor label="Полная статья" wire:model="full"
                 toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
            />

            <div class="flex flex-col gap-y-4 w-full">
                <div class="rounded-xl bg-zinc-800/50 -mx-4 px-4 py-4">
                    <flux:label class="text-xl">
                        Лексема <span class="font-serif" x-text="romanize(lexemes[selectedOrder][selectedSuborder].group)"></span> <span class="font-serif" x-text="(selectedOrder + 1) + '.' + (selectedSuborder + 1)"></span>
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
                                 class="[&_[data-slot=content]]:min-h-[2rem]"
                    />
                    <flux:editor label="Полная статья лексемы"
                                 x-model="lexemes[selectedOrder][selectedSuborder].full"
                                 toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
                    />
                </div>
            </div>

            <x-button class="mt-6" type="submit">Создать</x-button>
        </div>
    </form>
</div>
