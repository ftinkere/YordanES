@php declare(strict_types=1);

@endphp

<x-slot name="rightNavbar">
    <x-light-button variant="negative"
                    href="/dictionary/{{ $dictionaryArticle->uuid }}" wire:navigate
    >Отмена</x-light-button>
</x-slot>

<div>
    <form wire:submit="updateArticle" x-ref="form"
          x-data="() => ({
            // --- исходные state ---
            selectedOrder:    {{ array_key_last($lexemes) }},
            selectedSuborder: {{ array_key_last(last($lexemes)) }},
            lexemes:          $wire.entangle('lexemes'),

            // --- вспомогалки для работы с либой (array|object, sparse) ---
            getOrderKeys() {
              return Object.keys(this.lexemes)
                .map(n => Number(n))
                .filter(n => !isNaN(n))
                .sort((a, b) => a - b);
            },
            getSuborderKeys(order) {
              let bucket = this.lexemes[order] || {};
              return Object.keys(bucket)
                .map(n => Number(n))
                .filter(n => !isNaN(n))
                .sort((a, b) => a - b);
            },
            ensureOrderExists(idx) {
              if (!(idx in this.lexemes)) {
                let keys  = this.getOrderKeys();
                let below = keys.filter(n => n < idx).pop();
                let above = keys.find(n => n > idx);
                let ref   = this.lexemes[below ?? above];
                let last  = this.getSuborderKeys(below ?? above).pop();
                let group = ref[last].group;
                // создаём новый order с единственным suborder=0
                this.lexemes[idx] = { 0: { short: '', full: '', group } };
              }
            },
            ensureSuborderExists(order, sub) {
              this.ensureOrderExists(order);
              let bucket = this.lexemes[order];
              if (!(sub in bucket)) {
                let subs  = this.getSuborderKeys(order);
                let below = subs.filter(n => n < sub).pop();
                let above = subs.find(n => n > sub);
                let ref   = bucket[below ?? above];
                bucket[sub] = { short: '', full: '', group: ref.group };
              }
            },

            // --- публичный интерфейс — только инк/дек и индексы! ---
            incrementOrder() {
              let subs  = this.getSuborderKeys(this.selectedOrder + 1);
              this.selectedSuborder = subs.length ? subs.pop() : 0
              this.ensureOrderExists(this.selectedOrder + 1)
              this.ensureSuborderExists(this.selectedOrder + 1, this.selectedSuborder);
              this.selectedOrder++
            },
            decrementOrder() {
              if (this.selectedOrder > 0) {
                let subs  = this.getSuborderKeys(this.selectedOrder - 1);
                this.selectedSuborder = subs.length ? subs.pop() : 0
                this.ensureOrderExists(this.selectedOrder - 1)
                this.ensureSuborderExists(this.selectedOrder - 1, this.selectedSuborder);
                this.selectedOrder--
              };
            },
            incrementSuborder() {
              this.ensureSuborderExists(this.selectedOrder, this.selectedSuborder + 1);
              this.selectedSuborder++
            },
            decrementSuborder() {
              if (this.selectedSuborder > 0) {
                this.ensureSuborderExists(this.selectedOrder, this.selectedSuborder - 1);
                this.selectedSuborder--
              };
            },
          })"
    >
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

            <div class="flex flex-row gap-2">
                @foreach($dictionaryArticle->files as $file)
                    <div class="relative" x-on:deleted="$el.remove()">
                        <flux:icon.x-mark
                            wire:click="deleteImage('{{ $file->uuid }}')"
                            class="absolute right-0 top-0 rounded-full bg-zinc-800 border-zinc-900 hover:bg-rose-800 transition-colors"
                        />

                        <img src="{{ $file->path }}" alt="Изображение" width="{{ min(128, $file->width) }}" height="{{ min(128, $file->height) }}" />
                    </div>
                @endforeach
            </div>

            <x-filepond::upload wire:model="files" multiple accept="image/*" />

            <flux:editor label="Основная словарная статья" wire:model="article"
                         toolbar="heading | bold italic strike underline | bullet ordered blockquote | subscript superscript | link | align ~ x2i"
            />

            <div class="col-span-2">
                <flux:switch label="Публично доступно" wire:model="public" align="left" />
            </div>

            <div class="flex flex-col gap-y-4 w-full">
                <div class="rounded-xl bg-zinc-800/50 -mx-4 px-4 py-4">
                    <flux:label class="text-xl">
                        <span>Лексема <span class="font-serif" x-text="romanize(lexemes[selectedOrder][selectedSuborder].group)"></span> <span class="font-serif" x-text="(selectedOrder + 1) + '.' + (selectedSuborder + 1)"></span></span>
                    </flux:label>

                    <div class="flex flex-row justify-between gap-4">
                        <div></div>

                        <div class="flex flex-row gap-2">
                            <x-order-input x-model.number="lexemes[selectedOrder][selectedSuborder].group" romanize />
                            <x-order-input x-bind:class="{ 'newable': getOrderKeys().pop() == selectedOrder }"
                                           x-model.number="selectedOrder"
                                           x-on:increment="incrementOrder"
                                           x-on:decrement="decrementOrder"
                                           incrementShow />
                            <x-order-input x-bind:class="{ 'newable': getSuborderKeys(selectedOrder).pop() == selectedSuborder }"
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
