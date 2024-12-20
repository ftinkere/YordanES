<?php

namespace App\Livewire\Components;

use App\Services\UserService;
use Livewire\Component;

class Navbar extends Component
{
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
