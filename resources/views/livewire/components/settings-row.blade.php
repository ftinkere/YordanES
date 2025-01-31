<?php

declare(strict_types=1);

?>
<tr x-data="{ isEdit: false }">
    <td>
        {{ $name }}:
    </td>
    <td>
        <span x-show="! isEdit">{{ $value }}</span>
        <span x-show="isEdit">
            <input class="x-input-lite" wire:model.defer="value"/>
        </span>
    </td>
    <td>
        <x-link positive
                x-show="isEdit"
                x-on:click="isEdit = ! isEdit"
                wire:dirty
                wire:target="value"
                wire:click="applySetting"
                wire:confirm="Вы уверены?"
        >Применить</x-link>
        <x-link x-show="! isEdit" x-on:click="isEdit = true">Изменить</x-link>
        <x-link x-show="isEdit" x-on:click="isEdit = false" negative>Отменить</x-link>
    </td>
</tr><?php 
