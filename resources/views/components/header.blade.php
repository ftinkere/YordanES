@php declare(strict_types=1);
    use App\Models\User;

    /** @var User $user */
    $user = Auth::user();
@endphp

@props(['navlistAdd', 'title'])

<flux:header class="bg-zinc-300 dark:bg-zinc-800 shadow-lg flex flex-row items-center z-50">
    <flux:sidebar.toggle class="sm:hidden" icon="bars-2" inset="left" id="sidebar-toggle" />

    @if($title)
        <div class="max-sm:hidden rounded-lg hover:bg-zinc-800/5 dark:hover:bg-white/10">
            <flux:brand class="my-auto py-4 h-full! *:first:hidden ml-4 justify-center" href="/" wire:navigate>
                <x-slot name="name">
                    <span class="yordan-font text-2xl text-primary-500">Ëрдан</span>
                </x-slot>
            </flux:brand>
        </div>
    @endif

    <flux:navbar class="-mb-px max-sm:hidden">
        <flux:navbar.item icon="language" href="/languages" :current="false" wire:navigate>Все языки</flux:navbar.item>
    </flux:navbar>


    <flux:spacer/>

    <div class="ms-auto flex flex-row gap-2 items-center">
        @isset($right)
            {{ $right }}
        @endisset

        @auth
            <flux:dropdown>
                {{-- <flux:profile :avatar="$user->avatar" :chevron="false" class="first:*:rounded-full first:*:size-10!" /> --}}
                <flux:profile class="first:*:rounded-full" :avatar="$user->avatar ?? false" :name="$user->name" circle/>

                <flux:menu>
                    <div class="mb-4 pt-2 flex flex-col items-center text-black dark:text-zinc-400">
                        <flux:avatar circle :src="$user->avatar" :name="$user->name" size="lg" class="[:where(&)]:size-42! [:where(&)]:text-5xl!" />
                    </div>
                    <flux:menu.separator/>

                    <flux:menu.item icon="cog-6-tooth" href="/settings" wire:navigate>Настройки</flux:menu.item>
                    <flux:menu.separator/>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout" wire:navigate>Выйти
                    </flux:menu.item>

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

<flux:sidebar stashable sticky class="sm:hidden bg-zinc-50 dark:bg-zinc-800 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="sm:hidden" icon="x-mark"/>

    <flux:button variant="ghost" class="my-auto py-4 h-full! text-primary-600! text-[2rem]! yordan-font" href="/" wire:navigate>
        Ëрдан
    </flux:button>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="arrow-right-start-on-rectangle" href="/logout" wire:navigate>Выйти</flux:navlist.item>
        <flux:menu.separator/>
        <flux:navlist.item icon="cog-6-tooth" href="/settings" wire:navigate>Настройки</flux:navlist.item>
        <flux:menu.separator/>

    </flux:navlist>

    <flux:spacer/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="/" :current="false" wire:navigate>Главная страница</flux:navlist.item>
        <flux:navlist.item icon="language" href="/languages" :current="false" wire:navigate>Все языки</flux:navlist.item>

        @isset($navlistAdd)
            {{ $navlistAdd }}
        @endisset

        @isset($right)
            <flux:separator class="my-2" />
            {{ $right }}
        @endisset

    </flux:navlist>
</flux:sidebar>

<?php 
