<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')

        <title>{{ isset($title) ? $title . ' - Ëрдан' : 'Ëрдан' }}</title>

        <wireui:scripts />
    </head>
    <body class="bg-neutral-50 dark:bg-neutral-900">
        <div class="flex flex-col">
            <livewire:components.navbar />
            <livewire:components.message-bar />

            <div class="p-3 h-full">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
