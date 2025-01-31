<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\Factory;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IndexPage extends Component
{
    #[Locked]
    public ?User $user = null;
    public function __construct(private readonly AuthManager $authManager, private readonly Factory $viewFactory)
    {
    }

    public function mount(): void
    {
        $this->user = $this->authManager->user();
    }

    public function render()
    {
        return $this->viewFactory->make('livewire.index-page');
    }
}
