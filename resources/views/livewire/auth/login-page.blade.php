<x-card title="Вход" rounded="3xl" class="max-w-md mx-auto">
    <form wire:submit="login">
        <div class="flex flex-col gap-0.5">
            @csrf
            <x-input label="Юзернейм" placeholder="qwerty" wire:model="username" />
            <x-password label="Пароль" placeholder="*****" wire:model="password" />
            <span class="m-1"></span>
            <x-button type="submit">Войти</x-button>
        </div>
    </form>
</x-card>