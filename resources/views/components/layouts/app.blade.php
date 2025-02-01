<?php

declare(strict_types=1);

?>
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
        <div class="flex flex-col">
            <x-header>
                @if(isset($rightNavbar))
                    <x-slot:right>
                        {{ $rightNavbar }}
                    </x-slot:right>
                @endif
            </x-header>

            <livewire:components.message-bar />

            <div class="p-3 container mx-auto h-full">
                {{ $slot }}
            </div>
        </div>

        @fluxScripts
        @vite('resources/js/app.js')
        <wireui:scripts />
    </body>
</html>
<?php 
