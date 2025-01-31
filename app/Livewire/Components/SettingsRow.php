<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use Illuminate\Contracts\View\Factory;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class SettingsRow extends Component
{
    public string $name;
    public function __construct(private readonly Factory $viewFactory)
    {
    }

    #[Modelable]
    public string $value;

    #[Locked]
    public string $attribute;

    public bool $confirm = false;

    public function applySetting(): void
    {
        $this->dispatch('apply-setting', attribute: $this->attribute);
    }

    public function render()
    {
        return $this->viewFactory->make('livewire.components.settings-row');
    }
}
