<?php

namespace App\Livewire\Components;

use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Navbar extends Component
{
    #[Locked]
    public ?User $user = null;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function logout(UserService $service)
    {
        $service->logout();
        $this->redirect('/');
    }

    public function render()
    {
        return view('livewire.components.navbar');
    }
}
