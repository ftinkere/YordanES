<div class="bg-neutral-300 dark:bg-neutral-800  text-lg h-12 px-2">
    <div class="container mx-auto flex flex-row items-center">
        <div class="flex-grow inline-flex justify-start items-center">
            <x-button flat wire:navigate href="/"><span class="text-2xl">Ëрдан</span></x-button>
        </div>
        <div class="flex-grow inline-flex justify-end items-center">
            @auth
                <x-button flat secondary label="Выйти" wire:click="logout" />
            @endauth
            @guest
                <x-button flat secondary label="Войти" wire:navigate href="/login" />
            @endguest
        </div>
    </div>
</div>
