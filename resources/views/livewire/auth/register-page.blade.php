<x-card rounded="3xl" class="max-w-md mx-auto">
    <x-slot name="title">
        <span class="text-2xl">Регистрация</span>
    </x-slot>
    <form wire:submit="register">
        <div class="flex flex-col gap-0.5">
            @csrf
            <x-input label="Юзернейм" placeholder="qwerty" wire:model="username" />
            <x-input label="Отображаемое имя" placeholder="Андрей" wire:model="visible_name" />
            <x-input label="Почта" placeholder="example@yordan.ru" wire:model="email" type="email" />
            <x-password label="Пароль" placeholder="*****" wire:model="password" />
            <span class="m-1"></span>
            <x-password placeholder="*****" wire:model="password_repeat" />
            <span class="m-1"></span>
            <x-button type="submit">Регистрация</x-button>
        </div>
    </form>
</x-card>
