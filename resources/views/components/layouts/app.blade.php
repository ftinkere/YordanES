@php declare(strict_types=1); @endphp
@props([
    'rightNavbar',
    'navlistAdd',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />


        <title>{{ isset($title) ? $title . ' - Ëрдан' : 'Ëрдан' }}</title>

        @fluxAppearance
    </head>
    <body class="bg-zinc-50 dark:bg-zinc-900 min-h-[100dvh]" x-init="$flux.appearance = 'system'">

        <div class="flex flex-row">
            @isset($sidebar)
                <div class="max-sm:hidden bg-zinc-200 dark:bg-zinc-800 drop-shadow-lg">
                    <div class="rounded-lg hover:bg-zinc-800/5 dark:hover:bg-white/10">
                        <flux:brand class="my-auto py-4 h-full! *:first:hidden ml-4 justify-center" href="/" wire:navigate>
                            <x-slot name="name">
                                <span class="yordan-font text-2xl text-primary-500">Ëрдан</span>
                            </x-slot>
                        </flux:brand>
                    </div>

                    <flux:navlist class="h-[100dvh] min-w-[200px] md:sticky top-4 z-10 mt-4 p-2">
                        {{ $sidebar }}
                    </flux:navlist>
                </div>
            @endisset

            <div class="flex flex-col w-full grow">
                <x-header :title="!isset($sidebar)">
                    @isset($rightNavbar)
                        <x-slot:right>
                            {{ $rightNavbar }}
                        </x-slot:right>
                    @endisset
                    @isset($sidebar)
                        <x-slot:navlistAdd>
                            <flux:separator />
                            {{ $sidebar }}
                        </x-slot:navlistAdd>
                    @endisset
                </x-header>


                <div class="flex flex-row">


                    <div class="p-4 max-w-4xl mx-auto h-full w-full">
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>

        @fluxScripts
        @vite('resources/js/app.js')
        @filepondScripts
    </body>
</html>
