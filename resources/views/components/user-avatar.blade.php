<?php

declare(strict_types=1);

?>
@php use App\Models\User; @endphp
@props([
    'user' => null,
    'height' => '5rem',
])
@php
/**
* @var User $user
*/
@endphp
<div {{ $attributes }}>
    <img class="rounded-full aspect-square shadow-lg border border-zinc-500/10" src="{{ $user?->avatar }}" style="height: {{ $height }}; width: {{ $height }};" alt="аватар">
</div>
<?php 
