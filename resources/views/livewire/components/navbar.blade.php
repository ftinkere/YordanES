<div class="bg-neutral-300 dark:bg-neutral-800  text-lg h-12 px-2 z-10">
    <div class="container mx-auto flex flex-row items-center">
        <div class="flex-grow inline-flex justify-start items-center">
            <x-button flat
                      wire:navigate
                      href="/"><span class="text-2xl">Ëрдан</span></x-button>
        </div>
        <div class="flex-grow inline-flex justify-end items-center">
            @auth
                <x-dropdown>
                    <x-slot name="trigger">
                        <x-avatar
                                :label="$user->avatar ? null : mb_substr($user->name ?? 'А', 0, 1)"
                                :src="$user->avatar"
                                class="mt-1"
                        />
                    </x-slot>

                    <x-dropdown.item label="Настройки"
                                     wire:navigate
                                     href="/settings"/>
                    <x-dropdown.item label="Выйти"
                                     wire:click="logout"
                                     separator/>
                </x-dropdown>

            @endauth
            @guest
                <x-button flat
                          secondary
                          label="Войти"
                          wire:navigate
                          href="/login"/>
            @endguest
        </div>
    </div>
</div>
