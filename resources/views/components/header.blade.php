@php declare(strict_types=1);
    $user = Auth::user();
@endphp
<flux:header class="bg-zinc-300 dark:bg-zinc-800 shadow-lg flex flex-row items-center z-50">
    <flux:sidebar.toggle class="sm:hidden" icon="bars-2" inset="left" />

    <flux:button variant="ghost" class="my-auto py-4 !h-full !text-primary-600 !text-[2rem] yordan-font" href="/" wire:navigate>
        Ëрдан
    </flux:button>

    <flux:navbar class="-mb-px max-sm:hidden">
        <flux:navbar.item icon="language" href="/languages" :current="false" wire:navigate>Все языки</flux:navbar.item>
    </flux:navbar>


    <div class="ms-auto flex flex-row gap-2 items-center">
        @if (isset($right))
            {{ $right }}
        @endif

        @auth
        <flux:dropdown>
            {{-- <flux:profile :avatar="$user->avatar" :chevron="false" class="*:first:rounded-full *:first:!size-10" /> --}}
            <x-button flat xs>
                <x-avatar
                    :label="$user->avatar ? null : mb_substr($user->name ?? 'А', 0, 1)"
                    :src="$user->avatar"
                    class="mt-1"
                />
            </x-button>

            <flux:menu>
                <div class="mb-4 flex flex-col items-center text-black dark:text-neutral-400">
                    <x-avatar
                        :label="$user->avatar ? null : mb_substr($user->name ?? 'А', 0, 1)"
                        :src="$user->avatar"
                        size="h-32 w-32"
                        class="mt-1"
                    />

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

<flux:sidebar stashable sticky class="sm:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="sm:hidden" icon="x-mark" />

    <flux:button variant="ghost" class="my-auto py-4 !h-full !text-primary-600 !text-[2rem] yordan-font" href="/" wire:navigate>
        Ëрдан
    </flux:button>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="arrow-right-start-on-rectangle" href="/logout" wire:navigate>Выйти</flux:navlist.item>
        <flux:menu.separator />
        <flux:navlist.item icon="cog-6-tooth" href="/settings" wire:navigate>Настройки</flux:navlist.item>
        <flux:menu.separator />

    </flux:navlist>

    <flux:spacer />

    <flux:navlist variant="outline">
        <flux:navlist.item icon="language" href="/languages" :current="false" wire:navigate>Все языки</flux:navlist.item>
        <flux:navlist.item icon="home" href="/" :current="false" wire:navigate>Главная страница</flux:navlist.item>
    </flux:navlist>
</flux:sidebar>

<?php 
