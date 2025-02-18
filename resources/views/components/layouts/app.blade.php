@php declare(strict_types=1); @endphp
@props([
    'rightNavbar',
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

        @fluxStyles
    </head>
    <body class="bg-zinc-50 dark:bg-zinc-900">
        <div class="relative flex flex-col">
            <x-header>
                @if(isset($rightNavbar))
                    <x-slot:right>
                        {{ $rightNavbar }}
                    </x-slot:right>
                @endif
            </x-header>

            <livewire:components.message-bar />

            <div class="flex flex-col md:flex-row gap-1">
                @if (isset($sidebar))
                    <div class="md:w-64 pb-4 ml-4 mr-4 md:mr-10">
                        <div class="absolute р-14 top-0 -left-4 md:w-64 min-h-screen h-full w-full bg-zinc-200 dark:bg-zinc-800 -z-10 drop-shadow-lg"></div>
                        {{ $sidebar }}
                    </div>
                @endif

                <div class="p-3 container mx-auto h-full">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @fluxScripts
        @vite('resources/js/app.js')
        <wireui:scripts />
    </body>
</html>
