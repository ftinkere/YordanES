<?php declare(strict_types=1); ?>
<div>
    <x-card title="Восстановить пароль" class="max-w-md mx-auto">
        <form wire:submit="sendRecoveryLink">
            <div class="flex flex-col gap-1">
                <x-input type="email" wire:model="email" label="Почта" placeholder="example@yordan.ru" />
                <x-button type="submit">Восстановить</x-button>
            </div>
        </form>
    </x-card>
</div>
