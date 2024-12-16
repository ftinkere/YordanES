<x-card title="Регистрация" rounded="3xl">
    <form wire:submit="register">
        <x-input label="Юзернейм" placeholder="qwerty" wire:model="username" />
        <x-input label="Отображаемое имя" placeholder="Андрей" wire:model="visible_name" />
        <x-input label="Почта" placeholder="example@yordan.ru" wire:model="email" type="email" />
        <x-password label="Пароль" placeholder="*****" wire:model="password" />
        <span class="m-1"></span>
        <x-password placeholder="*****" wire:model="password_repeat" />
        <span class="m-1"></span>
        <x-button type="submit">Рега</x-button>
    </form>
</x-card>
