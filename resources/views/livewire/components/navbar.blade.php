<div class="bg-neutral-300 text-lg h-12 px-2 flex flex-row items-center">
    <div class="flex-grow inline-flex justify-start items-center">
        <span class="text-2xl">Ëрдан</span>
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
