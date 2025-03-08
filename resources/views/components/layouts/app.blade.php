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

        @fluxAppearance
    </head>
    <body class="bg-zinc-50 dark:bg-zinc-900 min-h-screen">
        <div class="flex flex-col h-screen">
            <x-header>
                @if(isset($rightNavbar))
                    <x-slot:right>
                        {{ $rightNavbar }}
                    </x-slot:right>
                @endif
            </x-header>


            <div class="relative grow grid grid-cols-4 max-md:grid-rows-[min-content_1fr] gap-4">
                @if (isset($sidebar))
                    <div class="col-span-4 md:col-span-1 bg-zinc-200 dark:bg-zinc-800 drop-shadow-lg">
                        {{ $sidebar }}
                    </div>
                @endif

                <div @class(['p-3 container mx-auto h-full col-span-4', 'md:col-span-3' => isset($sidebar)])>
                    {{ $slot }}
                </div>
            </div>
        </div>

        @fluxScripts
        @vite('resources/js/app.js')
        <wireui:scripts />
    </body>
</html>
