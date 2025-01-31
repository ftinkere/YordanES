<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use Illuminate\Contracts\View\Factory;
use Livewire\Component;

class MessageBar extends Component
{
    public function __construct(private readonly Factory $viewFactory)
    {
    }
    public function render()
    {
        return $this->viewFactory->make('livewire.components.message-bar');
    }
}
