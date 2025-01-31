<?php

declare(strict_types=1);

?>
<div class="container mx-auto px-4 w-full flex flex-col gap-2">
    <div class="group relative mx-auto">
        <x-avatar
                :label="$user->avatar ? null : mb_substr($user->name ?? 'А', 0, 1)"
                :src="$user->avatar"
                size="w-32 h-32"
                icon-size="2xl"
                class="group-hover:filter group-hover:brightness-50"
        />
        <span
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl text-white hidden group-hover:block cursor-pointer"
            x-on:click="document.getElementById('avatar-input').click()"
        >
            изменить
        </span>
        <input wire:model.live="avatar" id="avatar-input" class="hidden" type="file" accept="image/*" max="10240" >
    </div>

    <table class="settings-table">
        <livewire:components.settings-row
                name="Имя пользователя"
                attribute="username"
                wire:model="username"
        />

        <livewire:components.settings-row
                name="Отображаемое имя"
                attribute="name"
                wire:model="name"
        />

        <livewire:components.settings-row
                name="Почта"
                attribute="email"
                wire:model="email"
        />

        <tr class="!h-8">
            <td>
                @if($user->email_verified_at)
                    <div>
                        <x-icon name="check" class="h-4 inline text-green-600"/>
                        <span>Подтверждена</span>
                    </div>
                @else
                    <x-link
                            class="text-sm"
                            wire:click="resendEmailConfirmation"
                            x-data="{ show: true }"
                            x-show="show"
                            x-on:click="show = false"
                    >Подтвердить
                    </x-link>
                @endif
            </td>
        </tr>
    </table>
</div>
<?php 
