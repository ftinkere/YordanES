<?php

declare(strict_types=1);

?>
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
</x-card><?php 
