<?php

namespace App\Livewire\Components;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class SettingsRow extends Component
{
    public string $name;
    #[Modelable]
    public string $value;
    #[Locked]
    public string $attribute;

    public function applySetting(): void
    {
        $this->dispatch('apply-setting', attribute: $this->attribute);
    }

    public function render()
    {
        return view('livewire.components.settings-row');
    }
}
