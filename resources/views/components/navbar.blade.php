<?php

declare(strict_types=1);

?>
@props([
    'right',
])
@php
    $user = auth()->user();
@endphp

<div class="bg-neutral-300 dark:bg-neutral-800  text-lg h-fit px-2 z-10">
    <div class="container mx-auto flex flex-row items-center gap-2">
        <div class="flex-grow inline-flex gap-2 justify-start items-center">
            <x-button flat
                      wire:navigate
                      href="/"
            >
                <span class="text-2xl yordan-font">Ëрдан</span>
            </x-button>

            <x-button flat secondary
                      wire:navigate
                      href="/languages"
            >
                Все языки
            </x-button>
        </div>
        <div class="flex-grow inline-flex gap-2 justify-end items-center">
            @if (isset($right))
                {{ $right }}
            @endif
            @auth
                <x-dropdown>
                    <x-slot name="trigger">
                        <x-button flat xs>
                            <x-avatar
                                :label="$user->avatar ? null : mb_substr($user->name ?? 'А', 0, 1)"
                                :src="$user->avatar"
                                class="mt-1"
                            />
                        </x-button>
                    </x-slot>

                    <div class="mb-4 flex flex-col items-center text-black dark:text-neutral-400">
                        <x-avatar
                                :label="$user->avatar ? null : mb_substr($user->name ?? 'А', 0, 1)"
                                :src="$user->avatar"
                                class="mt-1"
                        />

                        <span class="text-md">{{ $user->name }}</span>
                        <span class="text-sm">{{ $user->username }}</span>
                    </div>

                    <x-dropdown.item
                            label="Настройки"
                            icon="cog-6-tooth"
                            wire:navigate
                            href="/settings"
                    />
                    <x-dropdown.item
                            label="Выйти"
                            icon="arrow-right-start-on-rectangle"
                            wire:navigate
                            href="/logout"
                            separator
                    />
                </x-dropdown>

            @endauth
            @guest
                <x-button
                        flat secondary
                        label="Войти"
                        wire:navigate
                        href="/login"
                />
            @endguest
        </div>
    </div>
</div>
<?php 
