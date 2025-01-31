<?php

declare(strict_types=1);

?>
@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp
<flux:header class="bg-zinc-300 dark:bg-zinc-800 shadow-lg">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <flux:button variant="ghost" class="my-auto py-4 !h-full !text-primary-600 !text-[2rem] yordan-font" href="/" wire:navigate>
        Ëрдан
    </flux:button>

    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="language" href="/languages" :current="false" wire:navigate>Все языки</flux:navbar.item>
    </flux:navbar>


    <div class="ms-auto flex flex-row gap-2">
        @if (isset($right))
            {{ $right }}
        @endif

        @auth
        <flux:dropdown>
            <flux:profile :avatar="$user->avatar" :chevron="false" class="*:first:rounded-full *:first:!size-10" />

            <flux:menu>
                <div class="mb-4 flex flex-col items-center text-black dark:text-neutral-400">
                    <x-user-avatar :user="$user" />

                    <span class="text-md">{{ $user->name }}</span>
                    <span class="text-sm">{{ $user->username }}</span>
                </div>
                <flux:menu.separator />

                <flux:menu.item icon="cog-6-tooth" href="/settings" wire:navigate>Настройки</flux:menu.item>
                <flux:menu.separator />
                <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout" wire:navigate>Выйти</flux:menu.item>

            </flux:menu>
        </flux:dropdown>
        @endauth
        @guest
                <flux:button variant="ghost" href="/login" wire:navigate>
                    Войти
                </flux:button>
        @endguest
    </div>
</flux:header>
<?php 
