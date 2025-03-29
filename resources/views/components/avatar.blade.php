@php declare(strict_types=1); @endphp
@props([
    'avatar' => null,
    'name' => null,
    'size' => '2rem',
    'textSize' => '0.6rem',
])
@php
    $initials ??= collect(explode(' ', $name ?? ''))
        ->map(fn($part) => Str::substr($part, 0, 1))
        ->filter()
        ->only([0, count(explode(' ', $name ?? '')) - 1])
        ->implode('');
@endphp

<div style="width: {{ $size }}; height: {{ $size }};" {{ $attributes->except('alt')->class('shrink-0 bg-zinc-200 overflow-hidden dark:bg-zinc-800 rounded-full') }}>
    <?php if (is_string($avatar)): ?>
        <img src="{{ $avatar }}" alt="{{ $attributes->get('alt') ?? 'Аватар пользователя ' . $name }}" />
    <?php elseif ($avatar): ?>
        {{ $avatar }}
    <?php else: ?>
        <div class="w-full h-full flex items-center justify-center text-xl" style="font-size: {{ $textSize }}">
            {{ $initials }}
        </div>
    <?php endif; ?>
</div>
