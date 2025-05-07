<div>
    <flux:tab.group>
        <div class="overflow-x-auto">
            <flux:tabs wire:model="tab">
                <flux:tab name="pos" icon="chat-bubble-bottom-center-text"
                    x-on:click="const url = new URL(window.location); url.searchParams.set('tab', 'pos'); history.replaceState({}, '', url);"
                >
                    Части речи
                </flux:tab>
                <flux:tab name="categories" icon="tag"
                    x-on:click="const url = new URL(window.location); url.searchParams.set('tab', 'categories'); history.replaceState({}, '', url);"
                >
                    Грамматические категории
                </flux:tab>
                <flux:tab name="values" icon="variable"
                  x-on:click="const url = new URL(window.location); url.searchParams.set('tab', 'values'); history.replaceState({}, '', url);"
                >
                    Грамматические значения
                </flux:tab>
            </flux:tabs>
        </div>

        <flux:tab.panel name="pos">
            @livewire('languages.grammaticConstructor.pos-tab', ['language' => $language])
        </flux:tab.panel>
        <flux:tab.panel name="categories">
            @livewire('languages.grammatic-constructor.categories-tab', ['language' => $language])
        </flux:tab.panel>
        <flux:tab.panel name="values">
            @livewire('languages.grammaticConstructor.values-tab', ['language' => $language])
        </flux:tab.panel>
    </flux:tab.group>
</div>
