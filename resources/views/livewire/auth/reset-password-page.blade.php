<?php

declare(strict_types=1);

?>
<x-card rounded="3xl" class="max-w-md mx-auto">
    <x-slot name="title">
        <span class="text-2xl">Восстановление пароля</span>
    </x-slot>
    <form wire:submit="resetPassword">
        <div class="flex flex-col gap-0.5">
            @csrf
            <x-password label="Новый пароль" placeholder="*****" wire:model="password" />
            <span class="m-1"></span>
            <x-password placeholder="*****" wire:model="password_repeat" />
            <span class="m-1"></span>
            <x-button type="submit">Восстановить</x-button>
        </div>
    </form>
</x-card><?php 
