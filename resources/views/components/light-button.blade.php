@php declare(strict_types=1); @endphp
@props([
     'variant' => 'positive',
     'solid' => false,
])

@php
    $color = match ($variant) {
        'primary' => 'amber',
        'secondary' => 'zinc',
        'positive' => 'teal',
        'negative' => 'rose',
        'warning' => 'yellow',
        'info' => 'sky',
        default => 'amber',
    }
@endphp

<flux:badge variant="{{ $solid ? 'solid' : null }}" color="{{ $color }}" as="button"
            {{ $attributes->class('cursor-pointer px-4! py-2! justify-center!') }}
>{{ $slot }}</flux:badge>


{{-- <flux:button variant="filled" {{ $attributes->class([ --}}
{{--         'bg-opacity-60! dark:bg-opacity-30!', --}}
{{--         'hover:bg-opacity-60! dark:hover:bg-opacity-30!', --}}
{{--         'focus:bg-opacity-60! dark:focus:bg-opacity-30! focus:ring-offset-2!', --}}

{{--         'text-primary-600! bg-primary-300/60! dark:bg-primary-600/30! dark:text-primary-300!' => $variant === 'primary', --}}
{{--         'hover:text-primary-800! hover:bg-primary-400/60! dark:hover:text-primary-100! dark:hover:bg-primary-500/30!' => $variant === 'primary', --}}
{{--         'focus:text-primary-900! focus:bg-primary-400/60! focus:ring-primary-400! dark:focus:text-primary-100! dark:focus:bg-primary-500!/30 dark:focus:ring-primary-700' => $variant === 'primary', --}}

{{--         'text-secondary-600! bg-secondary-300/60! dark:bg-secondary-600/30! dark:text-secondary-300!' => $variant === 'secondary', --}}
{{--         'hover:text-secondary-800! hover:bg-secondary-400/60! dark:hover:text-secondary-100! dark:hover:bg-secondary-500/30!' => $variant === 'secondary', --}}
{{--         'focus:text-secondary-800! focus:bg-secondary-400/60! focus:ring-secondary-400! dark:focus:text-secondary-100! dark:focus:bg-secondary-500/30! dark:focus:ring-secondary-700!' => $variant === 'secondary', --}}

{{--         'text-positive-600! bg-positive-300/60! dark:bg-positive-600/30! dark:text-positive-300!' => $variant === 'positive', --}}
{{--         'hover:text-positive-800! hover:bg-positive-400/60! dark:hover:text-positive-100! dark:hover:bg-positive-500/30!' => $variant === 'positive', --}}
{{--         'focus:text-positive-800! focus:bg-positive-400/60! focus:ring-positive-400! dark:focus:text-positive-100! dark:focus:bg-positive-500/30! dark:focus:ring-positive-700!' => $variant === 'positive', --}}

{{--         'text-negative-600! bg-negative-300/60! dark:bg-negative-600/30! dark:text-negative-300!' => $variant === 'negative', --}}
{{--         'hover:text-negative-800! hover:bg-negative-400/60! dark:hover:text-negative-100! dark:hover:bg-negative-500/30!' => $variant === 'negative', --}}
{{--         'focus:text-negative-800! focus:bg-negative-400/60! focus:ring-negative-400! dark:focus:text-negative-100! dark:focus:bg-negative-500/30! dark:focus:ring-negative-700!' => $variant === 'negative', --}}

{{--         'text-warning-600! bg-warning-300/60! dark:bg-warning-600! dark:text-warning-300!' => $variant === 'warning', --}}
{{--         'hover:text-warning-800! hover:bg-warning-400/60! dark:hover:text-warning-100! dark:hover:bg-warning-500/30!' => $variant === 'warning', --}}
{{--         'focus:text-warning-800! focus:bg-warning-400/60! focus:ring-warning-400! dark:focus:text-warning-100! dark:focus:bg-warning-500/30! dark:focus:ring-warning-700!' => $variant === 'warning', --}}

{{--         'text-info-600! bg-info-300/60! dark:bg-info-600! dark:text-info-300!' => $variant === 'info', --}}
{{--         'hover:text-info-800! hover:bg-info-400/60! dark:hover:text-info-100! dark:hover:bg-info-500/30!' => $variant === 'info', --}}
{{--         'focus:text-info-800! focus:bg-info-400/60! focus:ring-info-400! dark:focus:text-info-100! dark:focus:bg-info-500/30! dark:focus:ring-info-700!' => $variant === 'info', --}}
{{--     ]) }} > --}}
{{--     {{ $slot }} --}}
{{-- </flux:button> --}}
