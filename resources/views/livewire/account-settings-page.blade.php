@php declare(strict_types=1) @endphp
<div class="container mx-auto px-4 w-full flex flex-col gap-2">
    <div class="group relative mx-auto">
        <x-avatar
                :avatar="$user->avatar"
                :name="$user->name"
                size="12rem"
                textSize="5rem"
                class="group-hover:brightness-50 ease-in-out duration-200"
        />
        <span
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl text-white hidden group-hover:block cursor-pointer"
            x-on:click="document.getElementById('avatar-input').click()"
        >
            изменить
        </span>
        <input wire:model.live="avatar" id="avatar-input" class="hidden" type="file" accept="image/*" max="10240" >
    </div>
    @error('avatar')
        <p class="flex flex-row gap-2">
            <flux:icon.exclamation-triangle />
            {{ $message }}
        </p>
    @enderror

    <table class="settings-table">
        @php
            $rows = [
                ['label' => 'Имя пользователя', 'attribute' => 'username'],
                ['label' => 'Отображаемое имя', 'attribute' => 'name'],
                ['label' => 'Почта', 'attribute' => 'email'], // нужна последней пока что
            ];
        @endphp
        @foreach($rows as ['label' => $label, 'attribute' => $attribute])
            <tr x-data="{ isEdit: false }" class="h-16">
                <td>
                    {{ $label }}:
                </td>
                <td>
                    <span x-show="! isEdit">{{ $$attribute }}</span>
                    <span x-show="isEdit">
                        <input class="x-input-lite" wire:model="{{ $attribute }}"/>
                    </span>
                </td>
                <td>
                    <flux:link class="text-positive-500! cursor-pointer"
                            variant="ghost"
                            x-show="isEdit"
                            x-on:click="isEdit = ! isEdit"
                            wire:dirty
                            wire:target="{{ $attribute }}"
                            wire:confirm="Вы уверены?"
                            wire:click="$refresh"
                    >Применить</flux:link>
                    <flux:link variant="ghost" class="cursor-pointer" x-show="! isEdit" x-on:click="isEdit = true">Изменить</flux:link>
                    <flux:link variant="ghost" class="text-negative-500! cursor-pointer" x-show="isEdit" x-on:click="isEdit = false">Отменить</flux:link>
                </td>
            </tr>
            @error($attribute)
            <tr class="h-8!">
                <td colspan="3" class="text-negative-600">
                    <p class="flex flex-row gap-2">
                        <flux:icon.exclamation-triangle />
                        {{ $message }}
                    </p>
                </td>
            </tr>
            @enderror

        @endforeach

        <tr class="h-8!">
            <td>
                @if($user->email_verified_at)
                    <div>
                        <flux:icon.check class="h-4 inline text-positive-600"/>
                        <span>Подтверждена</span>
                    </div>
                @else
                    <flux:link class="text-positive-500! cursor-pointer text-sm"
                            wire:click="resendEmailConfirmation"
                            x-data="{ show: true }"
                            x-show="show"
                            x-on:click="show = false"
                    >Подтвердить</flux:link>
                @endif
            </td>
        </tr>
    </table>
</div>
<?php 
