<x-card>
    <form wire:submit="login">
        <x-input label="Юзернейм" placeholder="qwerty" wire:model="username" />
        <x-password label="Пароль" placeholder="*****" wire:model="password" />

        <x-button type="submit">Войти</x-button>
    </form>
</x-card>