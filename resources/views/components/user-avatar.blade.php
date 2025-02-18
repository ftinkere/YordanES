<?php

declare(strict_types=1);

?>
@php use App\Models\User; @endphp
@props([
    'user' => null,
    'size' => '10',
    'iconSize' => '2xl',
])
@php
/**
* @var User $user
*/
@endphp
<x-avatar
        :label="$user->avatar ? null : mb_substr($user->name ?? 'А', 0, 1)"
        :src="$user->avatar"
        size="w-{{ $size }}  h-{{ $size }}"
        icon-size="{{ $iconSize }}"
        class="group-hover:filter group-hover:brightness-50"
        {{ $attributes }}
/>
<?php 
