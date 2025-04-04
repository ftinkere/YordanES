<?php declare(strict_types=1); ?>

<flux:card class="space-y-6 max-w-md mx-auto">
    <div>
        <flux:heading size="lg">Регистрация</flux:heading>
    </div>

    <form class="space-y-6" wire:submit="register">
        <div class="space-y-6">
            <flux:field>
                <flux:input label="Никнейм" placeholder="Ваш никнейм" wire:model="username" />
                <flux:error name="username" />
            </flux:field>

            <flux:field>
                <flux:input label="Отображаемое имя" placeholder="Ваше Имя" wire:model="visible_name" />
                <flux:error name="visible_name" />
            </flux:field>

            <flux:field>
                <flux:input label="Почта" type="email" placeholder="Ваша почта" wire:model="email" />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Пароль</flux:label>
                <flux:input type="password" placeholder="Ваш пароль" wire:model="password" viewable />
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label>Повторение Пароля</flux:label>
                <flux:input type="password" placeholder="Ваш пароль" wire:model="password_repeat" viewable />
                <flux:error name="password_repeat" />
            </flux:field>
        </div>

        <flux:button type="submit" variant="primary" class="w-full mb-4">Зарегистрироваться</flux:button>
    </form>
</flux:card>