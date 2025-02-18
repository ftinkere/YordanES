<?php declare(strict_types=1); ?>
{{--
<x-card rounded="3xl" class="max-w-md mx-auto">
    <x-slot name="title">
        <span class="text-2xl">Вход</span>
    </x-slot>

    <form wire:submit="login">
        <div class="flex flex-col gap-0.5">
            @csrf
            <x-input label="Юзернейм" placeholder="qwerty" wire:model="username" />
            <x-password label="Пароль" placeholder="*****" wire:model="password" />
            <span class="m-1"></span>
            <x-button type="submit">Войти</x-button>
            <span class="m-1.5"></span>
            <div class="flex flex-row justify-between">
                <x-link color="sky" class="!underline" wire:navigate href="/forgot-password">Забыли пароль?</x-link>
                <x-link color="sky" class="!underline" wire:navigate href="/register">Регистрация</x-link>
            </div>
        </div>
    </form>
</x-card>
--}}

<flux:card class="space-y-6 max-w-md mx-auto">
    <div>
        <flux:heading size="lg">Войти</flux:heading>
        <flux:subheading>Добро пожаловать!</flux:subheading>
    </div>

    <form class="space-y-6" wire:submit="login">
        <div class="space-y-6">
            <flux:field>
                <flux:input label="Никнейм" placeholder="Ваш никнейм" wire:model="username" />
                <flux:error name="username" />
            </flux:field>

            <flux:field>
                <div class="mb-3 flex justify-between">
                    <flux:label>Пароль</flux:label>

                    <flux:link variant="subtle" class="text-sm" wire:navigate href="/forgot-password">
                        Восстановить пароль
                    </flux:link>
                </div>

                <flux:input type="password" placeholder="Ваш пароль" wire:model="password" />

                <flux:error name="password" />
            </flux:field>
        </div>

        <div class="space-y-2">
            <flux:button type="submit" variant="primary" class="w-full mb-4">Войти</flux:button>

            <flux:link variant="ghost" class="w-full" wire:navigate href="/register">Зарегистрироваться</flux:link>
        </div>
    </form>
</flux:card>

