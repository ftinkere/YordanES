@php declare(strict_types=1) @endphp
<div>
    <flux:card title="Вы"
               class="border max-w-md mx-auto"
    >
        <div class="flex flex-col ">
            @auth
                <span>{{ $user->name }} (<span>{{ $user->username }}</span>)</span>

                <span>
                    Почта: <span>{{ $user->email }}</span>
                    @if($user->email_verified_at)
                        <flux:icon.check class="h-4 inline text-positive-600"/>
                        <span>{{ $user->email_verified_at->format('Y-m-d H:i:s') }}</span>
                    @else
                        <flux:icon.x-mark class="h-4 inline text-negative-600"/>
                        <x-light-button
                                wire:click="resendEmailConfirmation()"
                                x-data="{ show: true }"
                                x-show="show"
                                x-on:click="show = false"
                        >Переотправить</x-light-button>
                    @endif
                </span>

                <span class="text-gray-400">uuid: <span>{{ $user->uuid }}</span></span>
            @endauth
            @guest
                <span>Не авторизованы(</span>
            @endguest
        </div>
    </flux:card>
</div>
<?php 
