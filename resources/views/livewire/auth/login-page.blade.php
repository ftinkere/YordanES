<?php declare(strict_types=1); ?>


<flux:card class="space-y-6 max-w-md mx-auto">
    <div>
        <flux:heading size="lg">Войти</flux:heading>
        <flux:subheading>Добро пожаловать!</flux:subheading>
    </div>

    <form class="space-y-6" wire:submit="login">
        <div class="space-y-6">
            <flux:field>
                <flux:input label="Никнейм" placeholder="Ваш никнейм" wire:model="username" />
                <flux:error name="username" />
            </flux:field>

            <flux:field>
                <div class="mb-3 flex justify-between">
                    <flux:label>Пароль</flux:label>

                    <flux:link variant="subtle" class="text-sm" wire:navigate href="/forgot-password">
                        Восстановить пароль
                    </flux:link>
                </div>

                <flux:input type="password" placeholder="Ваш пароль" wire:model="password" />

                <flux:error name="password" />
            </flux:field>
        </div>

        <div class="space-y-2">
            <flux:button type="submit" variant="primary" class="w-full mb-4">Войти</flux:button>

            <flux:link variant="ghost" class="w-full" wire:navigate href="/register">Зарегистрироваться</flux:link>
        </div>
    </form>
</flux:card>

